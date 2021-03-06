<?php

/**
 * Content side-report listing pages with broken links
 * @package cms
 * @subpackage content
 */

class BrokenLinksReport extends SS_Report {

	function title() {
		return _t('BrokenLinksReport.BROKENLINKS',"Broken links report");
	}
	
	function sourceRecords($params, $sort, $limit) {
		$join = '';
		$sortBrokenReason = false;
		if($sort) {
			$parts = explode(' ', $sort);
			$field = $parts[0];
			$direction = $parts[1];
			
			if($field == 'AbsoluteLink') {
				$sort = 'URLSegment ' . $direction;
			} elseif($field == 'Subsite.Title') {
				$join = 'LEFT JOIN "Subsite" ON "Subsite"."ID" = "SiteTree"."SubsiteID"';
			} elseif($field == 'BrokenReason') {
				$sortBrokenReason = true;
				$sort = '';
			}
		}
		$q = DB::USE_ANSI_SQL ? '"' : '`';
		if (!isset($_REQUEST['CheckSite']) || $params['CheckSite'] == 'Published') $ret = Versioned::get_by_stage('SiteTree', 'Live', "({$q}SiteTree{$q}.{$q}HasBrokenLink{$q} = 1 OR {$q}SiteTree{$q}.{$q}HasBrokenFile{$q} = 1)", $sort, $join, $limit);
		else $ret = DataObject::get('SiteTree', "({$q}SiteTree{$q}.{$q}HasBrokenFile{$q} = 1 OR {$q}HasBrokenLink{$q} = 1)", $sort, $join, $limit);
		
		$returnSet = new DataObjectSet();
		if ($ret) foreach($ret as $record) {
			$reason = false;
			$isRedirectorPage = in_array($record->ClassName, ClassInfo::subclassesFor('RedirectorPage'));
			$isVirtualPage = in_array($record->ClassName, ClassInfo::subclassesFor('VirtualPage'));
			
			if ($isVirtualPage) {
				if ($record->HasBrokenLink) {
					$reason = _t('BrokenLinksReport.VirtualPageNonExistent', "virtual page pointing to non-existent page");
					$reasonCodes = array("VPBROKENLINK");
				}
			} else if ($isRedirectorPage) {
				if ($record->HasBrokenLink) {
					$reason = _t('BrokenLinksReport.RedirectorNonExistent', "redirector page pointing to non-existent page");
					$reasonCodes = array("RPBROKENLINK");
				}
			} else {
				if ($record->HasBrokenLink && $record->HasBrokenFile) {
					$reason = _t('BrokenLinksReport.HasBrokenLinkAndFile', "has broken link and file");
					$reasonCodes = array("BROKENFILE", "BROKENLINK");
				} else if ($record->HasBrokenLink && !$record->HasBrokenFile) {
					$reason = _t('BrokenLinksReport.HasBrokenLink', "has broken link");
					$reasonCodes = array("BROKENLINK");
				} else if (!$record->HasBrokenLink && $record->HasBrokenFile) {
					$reason = _t('BrokenLinksReport.HasBrokenFile', "has broken file");
					$reasonCodes = array("BROKENFILE");
				}
			}
			
			if ($reason) {
				if (isset($params['Reason']) && $params['Reason'] && !in_array($params['Reason'], $reasonCodes)) continue;
				$record->BrokenReason = $reason;
				$returnSet->push($record);
			}
		}
		
		if($sortBrokenReason) $returnSet->sort('BrokenReason', $direction);
		
		return $returnSet;
	}
	function columns() {
		if(isset($_REQUEST['CheckSite']) && $_REQUEST['CheckSite'] == 'Draft') {
			$dateTitle = _t('BrokenLinksReport.ColumnDateLastModified', 'Date last modified');
		} else {
			$dateTitle = _t('BrokenLinksReport.ColumnDateLastPublished', 'Date last published');
		}
		
		$fields = array(
			"Title" => array(
				"title" => _t('BrokenLinksReport.PageName', 'Page name'),
				'formatting' => sprintf(
					'<a href=\"admin/show/$ID\" title=\"%s\">$value</a>',
					_t('BrokenLinksReport.HoverTitleEditPage', 'Edit page')
				)
			),
			"LastEdited" => array(
				"title" => $dateTitle,
				'casting' => 'SS_Datetime->Full'
			),
			"BrokenReason" => array(
				"title" => _t('BrokenLinksReport.ColumnProblemType', "Problem type")
			),
			'AbsoluteLink' => array(
				'title' => _t('BrokenLinksReport.ColumnURL', 'URL'),
				'formatting' => '$value " . ($AbsoluteLiveLink ? "<a target=\"_blank\" href=\"$AbsoluteLiveLink\">(live)</a>" : "") . " <a target=\"_blank\" href=\"$value?stage=Stage\">(draft)</a>'
			)
		);
		
		return $fields;
	}
	function parameterFields() {
		return new FieldSet(
			new DropdownField('CheckSite', _t('BrokenLinksReport.CheckSite','Check site'), array(
				'Published' => _t('BrokenLinksReport.CheckSiteDropdownPublished', 'Published Site'),
				'Draft' => _t('BrokenLinksReport.CheckSiteDropdownDraft', 'Draft Site')
			)),
			new DropdownField(
				'Reason', 
				_t('BrokenLinksReport.ReasonDropdown', 'Problem to check'), 
				array(
					'' => _t('BrokenLinksReport.Any', 'Any'),
					'BROKENFILE' => _t('BrokenLinksReport.ReasonDropdownBROKENFILE', 'Broken file'),
					'BROKENLINK' => _t('BrokenLinksReport.ReasonDropdownBROKENLINK', 'Broken link'),
					'VPBROKENLINK' => _t('BrokenLinksReport.ReasonDropdownVPBROKENLINK', 'Virtual page pointing to non-existent page'),
					'RPBROKENLINK' => _t('BrokenLinksReport.ReasonDropdownRPBROKENLINK', 'Redirector page pointing to non-existent page'),
				)
			)
		);
	}
}
