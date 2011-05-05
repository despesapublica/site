<div class="mainContent">
    <div class="typography">
        <% if Level(2) %>
        <% include BreadCrumbs %>
        <% end_if %>

        <h2>$Title</h2>
        <div class="content">
         <!-- AddThis Button BEGIN -->
            <div class="addthis_toolbox addthis_default_style ">
                <a class="addthis_counter"></a>
            </div>
            <script type="text/javascript">var addthis_config = {"data_track_clickback":false};</script>
            <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4d9661bf36f5be25"></script>
            <!-- AddThis Button END -->

            $Content
        </div>

    </div>

    <script type='text/javascript'>
        jQuery(document).ready(function(){
            jQuery("#list2").jqGridOpenData({
                url:'$gridLinkRequest',
                colNames:['Nif', 'Nome Empresa', 'Data Criação Emp.', 'Nº A.D. <br/>$getAno2Estatistica', 'Total A.D. <br/>$getAno2Estatistica', 'Nº A.D. <br/>$getAnoEstatistica', 'Total A.D. <br/>$getAnoEstatistica', 'Nº. A.D.', 'Total A.D.'],
                colModel:[
                    {name:'Nif',index:'Nif', width:50},
                    {name:'Nome',index:'Nome', width:140},
                    {name:'DataCriacao',index:'DataCriacao', width:60, formatter:'date'},
                    {name:'AdNumTotal2Ano',index:'AdNumTotal2Ano', width:40},
                    {name:'adValorTotal2Ano',index:'adValorTotal2Ano', width:65, align:"right", formatter:'currency'},
                    {name:'AdNumTotalAno',index:'AdNumTotalAno', width:40},
                    {name:'adValorTotalAno',index:'adValorTotalAno', width:65, align:"right", formatter:'currency'},
                    {name:'AdNumTotal',index:'AdNumTotal', width:40},
                    {name:'adValorTotal',index:'adValorTotal', width:70, align:"right", formatter:'currency'}
                ],
                pager: jQuery('#pager2'),
                sortname: 'DataCriacao',
                sortorder: "Desc",
                caption:"Entidades",
                gridview: false,
                subGrid : true,
                subGridRowExpanded: showSubgrid
            });

            //below code is for high-lighting the link and scroll to particular DOM Element as well
            jQuery(".firstUL li").removeClass("selected"); //Initially remove "selected" class if any
            jQuery(".firstUL li").each(function() {
                elementId = $(this).attr("id");
                if(elementId == jQuery("#nomeStartWith").val()){
                    $(this).addClass("selected"); //Add "selected" class for the clicked one
                }

                jQuery(this).click(function() { //On click of any Alphabet
                    jQuery(".firstUL li").removeClass("selected"); //Initially remove "selected" class if any
                    jQuery(this).addClass("selected"); //Add "selected" class for the clicked one
                    elementClick = jQuery(this).attr("id"); //get respective 'Id' for example 'a','b','c'.. etc.,

                    jQuery("#nomeStartWith").val(elementClick);
                    jQuery("#search-form").submit();
                });
            });

            jQuery( "#nTotalAdjudicacoesSlider" ).slider({
                range: true,
                min: 0,
                max:  $getMaxTotalAdjudicacoes,
                values: [ jQuery("#nTotalAdjudicacoesMin").val(), jQuery("#nTotalAdjudicacoesMax").val() ],
                slide: function( event, ui ) {
                    jQuery( "#nTotalAdjudicacoesLabel" ).html( ui.values[ 0 ] + " - " + ui.values[ 1 ] );
                    jQuery( "#nTotalAdjudicacoesMin" ).val( ui.values[ 0 ]);
                    jQuery( "#nTotalAdjudicacoesMax" ).val( ui.values[ 1 ]);
                }
            });
            jQuery( "#nTotalAdjudicacoesLabel" ).html( jQuery("#nTotalAdjudicacoesMin").val() +
                " - " + jQuery("#nTotalAdjudicacoesMax").val() );
        
             $('#search-precoTotalMin').inputmask('9 999 999 999,99 €', { 'numericInput': true, 'autounmask' : true});    //   123456  =>  € ___.__1.234,56
             $('#search-precoTotalMax').inputmask('9 999 999 999,99 €', { 'numericInput': true, 'autounmask' : true});    //   123456  =>  € ___.__1.234,56

        });

        function showSubgrid(subgrid_id, row_id) {
            // we pass two parameters
            // subgrid_id is a id of the div tag created whitin a table data
            // the id of this elemenet is a combination of the "sg_" + id of the row
            // the row_id is the id of the row
            // If we wan to pass additinal parameters to the url we can use
            // a method getRowData(row_id) - which returns associative array in type name-value
            // here we can easy construct the flowing
            var subgrid_table_id, pager_id, profundidade;
            subgrid_table_id = subgrid_id+"_t";
            pager_id = "p_"+subgrid_table_id;
            //profundidade = numOcorrencias(subgrid_table_id, "_t_")+1;
            $("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");

            jQuery("#"+subgrid_table_id).jqGrid({
                url:"AjustesDirectosService/pesquisa/?idEntidade="+row_id,
                datatype: "json",
                colNames:['Descricao', 'Pre&ccedil;o', 'Data Publica&ccedil;&atilde;o', 'Data contrato', 'entidade'],
                colModel:[
                    {name:'Descricao',index:'Descricao', width:200},
                    {name:'Preco',index:'Preco', width:60, align:"right", formatter:'currency'},
                    {name:'DataPublicacao',index:'DataPublicacao', width:60, formatter:'date'},
                    {name:'DataContrato',index:'DataContrato', width:60, formatter:'date'},
                    {name:'ec_Nome',index:'ec_Nome', width:200}
                ],
                rowNum:10,
                rowList:[10,20,40],
                rownumbers: true,
                rownumWidth: 30,
                pager: pager_id,
                sortname: 'DataPublicacao',
                sortorder: "Desc",
                height: '100%',
                autowidth: true,
                sortable: true,
                rownumbers: false,
                viewrecords: true
            });
        }

         function validate_form(){
            $('#search-precoTotalMin').inputmask({'mask':'999999999,99', 'numericInput': true, 'autounmask' : true, 'placeholder':'' });
            $('#search-precoTotalMax').inputmask({'mask':'999999999,99', 'numericInput': true, 'autounmask' : true, 'placeholder':'' });
        }
    </script>

    <div id="mysearch"></div>

    <div class="pesquisaBox" class="ui-widget ui-helper-reset">
        <h3 class="ui-helper-reset ui-state-default ui-corner-top">
            <span class="ui-icon ui-icon-search"></span><span class="title">Pesquisa</span></h3>
        <div class="ui-helper-reset ui-widget-content ui-corner-bottom">
            <form method="get" action="$Link" name="search-form" id="search-form" onsubmit="return validate_form(this)">
               <input type="hidden" name="nomeStartWith" id="nomeStartWith" value="$searchParams.nomeStartWith" />
                <table width="100%" cellpadding="2">
                    <tr>
                        <td class="label"><label for="search-keywords">Procurar por </label></td>
                        <td><input type="text" name="sk" id="search-keywords" value="$searchParams.sk" size="50" /></td>
                    </tr>
                    <tr>
                        <td class="label"><label for="tipoEmpresa">Tipo </label></td>
                        <td> $getTipoEntidadesSelect.Field</td>
                    </tr>
                    <tr>
                        <td class="label"><label for="search-precoMin">Preço total </label></td>
                        <td>
                            <input type="text" name="precoTotalMin" id="search-precoTotalMin" value="$searchParams.precoTotalMin.NiceAmountOnly" size="15" />
a
                            <input type="text" name="precoTotalMax" id="search-precoTotalMax" value="$searchParams.precoTotalMax.NiceAmountOnly" size="15" /><span class="discreto"> máximo: $getMaxPrecoTotalAdjudicacoes.Nice</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="label"><label for="nTotalAdjudicacoes">Nº de Adjudicações </label></td>
                        <td>
                            <input type="hidden" name="nTotalAdjudicacoesMin" id="nTotalAdjudicacoesMin" value="$searchParams.nTotalAdjudicacoesMin" />
                            <input type="hidden" name="nTotalAdjudicacoesMax" id="nTotalAdjudicacoesMax" value="$searchParams.nTotalAdjudicacoesMax" />
                            <div id="nTotalAdjudicacoesSlider"></div>
                            <div id="nTotalAdjudicacoesLabel"></div>
                        </td>
                    </tr>
                </table>
                <div class="buttons">
                    <input type="submit" value="Pesquisar" />
                </div>
            </form>
        </div>
    </div>

    <div  class="alphaFilter ui-widget ui-helper-reset">
        <ul class="firstUL ui-helper-reset ui-widget-header ui-corner-all ">
            <li id="" class="selected">...</li>
            <li id="a">A</li>
            <li id="b">B</li>
            <li id="c">C</li>
            <li id="d">D</li>
            <li id="e">E</li>
            <li id="f">F</li>
            <li id="g">G</li>
            <li id="h">H</li>
            <li id="i">I</li>
            <li id="j">J</li>
            <li id="k">K</li>
            <li id="l">L</li>
            <li id="m">M</li>
            <li id="n">N</li>
            <li id="o">O</li>
            <li id="p">P</li>
            <li id="q">Q</li>
            <li id="r">R</li>
            <li id="s">S</li>
            <li id="t">T</li>
            <li id="u">U</li>
            <li id="v">V</li>
            <li id="x">X</li>
            <li id="z">Z</li>
        </ul>
    </div>

    <div id="gridEntidades" class="grid">
        <table id="list2"></table>
        <div id="pager2"></div>
    </div>
</div>