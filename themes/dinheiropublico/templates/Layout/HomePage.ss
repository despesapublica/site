

<div id="contentHeading" class="typography">
	       $Content
</div>

<div class="rightWrapper">
    <!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
<a class="addthis_counter addthis_pill_style"></a>
</div>
<script type="text/javascript">var addthis_config = {"data_track_clickback":true};</script>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4d9661bf36f5be25"></script>
<!-- AddThis Button END -->

    <div class="box boxIndicadoresHomepage">
        <h2 class="cufon">Indicadores</h2>

        <% cached 'indicadoresHomepage', getDataLastADUpdate %>
            <% include IndicadoresHomepage %>
        <% end_cached %>
        <div class="clear"></div>
    </div>
    <div id="recentcomments" class="box boxUltimosComentariosHomepage">
        <h2 class="cufon">Últimos Comentários</h2>
        <div class="dsq-widget">
           <script type="text/javascript" src="http://dinheiropublico.disqus.com/recent_comments_widget.js?num_items=10&hide_avatars=0&avatar_size=48&excerpt_length=160"></script>
        </div>
        <div class="clear"></div>
    </div>
    <div style="margin-top:20px;float:left;">
         <iframe src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FDespesa-P%C3%BAblica%2F196404600397657&amp;width=290&amp;colorscheme=light&amp;show_faces=true&amp;stream=true&amp;header=false&amp;height=615" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:290px; height:615px;" allowTransparency="true"></iframe>
    </div>

</div>
<div class="centerWrapper">
        <div class="box" id="boxSearchAdjudicacoes">
            <h2>Pesquisar Ajustes Directos</h2>
            <form method="get" action="ajustes-directos" name="search-form" id="search-formAdjudicacoes" class="boxContent">
                <div class="fields">
                    <label for="search-keywords">Procurar por </label><input type="text" name="sk" id="search-keywords" value="" size="25" />
                </div>
                <div class="buttons">
                    <input type="submit" value="Pesquisar" />
                </div>
                <div><a href="ajustes-directos">Pesquisa avançada</a></div>
            </form>

        </div>

        <div class="box" id="boxSearchEntidades">
            <h2>Pesquisar Entidades</h2>
            <form method="get" action="entidades" name="search-form" id="search-formEntidade" class="boxContent">
                <input type="hidden" name="page" value="entidades-pesquisa" />
                <div class="fields">
                    <label for="search-keywords">Procurar por </label><input type="text" name="sk" id="search-keywords" value="" size="25" />
                </div>
                <div class="buttons">
                    <input type="submit" value="Pesquisar" />
                </div>
                <div><a href="entidades">Pesquisa avançada</a></div>
            </form>
        </div>
        <div class="clear"></div>


<script type='text/javascript'>
    jQuery(document).ready(function(){
        jQuery("#list2").jqGridOpenData({
            url:'AjustesDirectosService/pesquisa/?last=1',
            colNames:['Descricao', 'Pre&ccedil;o', 'Data Publica&ccedil;&atilde;o', 'Data contrato', 'Adjudicante', 'Contratado'],
            colModel:[
                {name:'Descricao',index:'Descricao', width:150},
                {name:'Preco',index:'Preco', width:70, align:"right", formatter:'currency'},
                {name:'DataPublicacao',index:'DataPublicacao', width:60, formatter:'date'},
                {name:'DataContrato',index:'DataContrato', width:60, formatter:'date', hidden:true},
                {name:'ea_Nome',index:'ea_Nome', width:110},
                {name:'ec_Nome',index:'ec_Nome', width:110}
            ],
            pager: jQuery('#pager2'),
            sortname: "DataPublicacao",
            sortorder: "Desc",
            caption:"Últimos Ajustes Directos",
            rowNum:20,
			rowList:[10,20,40]
        });
    });
</script>

  <script type='text/javascript'>
    jQuery(document).ready(function(){
    $(".dsq-widget-meta").children("a").each( function(index) { $(this).attr("href",$(this).attr("href").replace(/.*despesapublica\.com\//,"/").replace(/.*site3\//,"/").replace(/\/view\/view\/ad_/,"/view/?ID=") ) }  );
    });
  </script>

  <div class="grid" style="margin-top:26px">
    <table id="list2"></table>
    <div id="pager2"></div>
</div>

</div>