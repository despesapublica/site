<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">


<html lang="$ContentLocale">
    <head> 
        <% base_tag %>
        <title>$MetaTitle</title>
		$MetaTags(false)
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <meta property="og:title" content="$MetaTitle" />
        <meta property="og:description" content="$MetaDescription" />
        <meta property="og:image" content="{$siteRoot}themes/dinheiropublico/images/logo.png" />


        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/jquery-ui.min.js"></script>
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>

        <% require css(dinheiropublico/javascript/superfish/css/superfish.css) %>
        <% require css(dinheiropublico/javascript/superfish/css/superfish-navbar.css) %>

        
        <!--[if IE 6]>
			<style type="text/css">
			 @import url(themes/dinheiropublico/css/ie6.css);
			</style> 
		<![endif]-->

        <script type='text/javascript'>
            $(document).ready(function(){
                $("ul.sf-menu").superfish({
                    pathClass:  'current'
                });
                $("table.rowAlternate tr:nth-child(even)").addClass('rowAlt');
            });

        </script>
        <script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-22887629-1']);
  _gaq.push(['_setDomainName', '.despesapublica.com']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script> 
    </head>
    <body>
        <div id="BgContainer">
            <div id="Container">
                <div id="Header">
                    <div id="HeaderWrapper">
                        <div id="mastheadLeft">
                            <h1><a href="index.php">$SiteConfig.Title</a></h1>
                            <h2>$SiteConfig.Tagline</h2>
                        </div>
                        <div id="mastheadRight">
                            <% cached 'indicadoresTopo', getDataLastADUpdate %>
                                <% include IndicadoresTopo %>
                            <% end_cached %>
                        </div>

                        <div class="facebookTopo">
                        <iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FDespesa-P%25C3%25BAblica%2F196404600397657&amp;layout=button_count&amp;show_faces=false&amp;width=110&amp;action=like&amp;font&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:110px; height:21px;" allowTransparency="true"></iframe>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>

                <div id="Navigation">
                    <% include Navigation %>
                    <div class="clear"><!-- --></div>
                </div>

                <div class="clear"><!-- --></div>

                <div id="Layout">
			  $Layout
                </div>

                <div class="clear"><!-- --></div>
            </div>
            <div id="Footer">
                <% include Footer %>
            </div>
        </div>
    </body>
</html>