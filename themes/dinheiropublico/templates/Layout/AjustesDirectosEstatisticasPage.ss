<div class="mainContent">
    <div class="typography">
        <% if Level(2) %>
        <% include BreadCrumbs %>
        <% end_if %>

        <h2>$Title</h2>

        <script type='text/javascript'>
            jQuery(document).ready(function(){
                var tabs = jQuery("#tabsEstatisticas li a");
                jQuery(tabs[0]).attr("href", "#tabs-TopADCaras");
                jQuery(tabs[1]).attr("href", "#tabs-TopADBaratas");
                jQuery(tabs[2]).attr("href", "#tabs-TopEntidadesA");
                jQuery(tabs[3]).attr("href", "#tabs-TopEntidadesC");
                jQuery(tabs[4]).attr("href", "#tabs-GraficoMensal");
                jQuery(tabs[5]).attr("href", "#tabs-GraficoTemporal");
            });
        </script>

        <div class="content">
            <!-- AddThis Button BEGIN -->
            <div class="addthis_toolbox addthis_default_style ">
                <a class="addthis_counter"></a>
            </div>
            <script type="text/javascript">var addthis_config = {"data_track_clickback":false};</script>
            <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4d9661bf36f5be25"></script>
            <!-- AddThis Button END -->

            $Content
            <br/><br/><br/><br/><br/><br/>
        </div>
    </div>
    <script type='text/javascript'>
        jQuery(document).ready(function(){
            var initialized = [false,false,false,false,false,false];
            $('#tabsEstatisticas').tabs
            (
            {
                show: function(event, ui)
                {
                    if(ui.index == 0 && !initialized[0])
                    {
                        TopADCaras();
                        initialized[0] = true;
                    }
                    else if(ui.index == 1 && !initialized[1])
                    {
                        TopADBaratas();
                        initialized[1] = true;
                    }
                    else if(ui.index == 2 && !initialized[2])
                    {
                        TopEntidadesA();
                        initialized[2] = true;
                    }
                    else if(ui.index == 3 && !initialized[3])
                    {
                        TopEntidadesC();
                        initialized[3] = true;
                    }
                    else if(ui.index == 4 && !initialized[4])
                    {
                        GraficoMensal();
                        initialized[4] = true;
                    }
                    else if(ui.index == 5 && !initialized[5])
                    {
                        GraficoTemporal();
                        initialized[5] = true;
                    }
                }
            }
        );
        });
 
        function TopADCaras(){
            jQuery("#listTopADCaras").jqGridOpenData({
                url:'AjustesDirectosService/top/',
                colNames:['Descricao', 'Pre&ccedil;o', 'Data Publica&ccedil;&atilde;o', 'Data contrato', 'Adjudicante', 'Contratado'],
                colModel:[
                    {name:'Descricao',index:'Descricao', width:200},
                    {name:'Preco',index:'Preco', width:80, align:"right", formatter:'currency'},
                    {name:'DataPublicacao',index:'DataPublicacao', width:70, formatter:'date'},
                    {name:'DataContrato',index:'DataContrato', width:65, formatter:'date'},
                    {name:'ea_Nome',index:'ea_Nome', width:150},
                    {name:'ec_Nome',index:'ec_Nome', width:150}
                ],
                sortname: "Preco",
                sortorder: "Desc",
                rowNum:50,
                hasHistory: false,
                toppager:false,
                loadonce: true,
                caption:""
            });
        }

        function TopADBaratas(){
            jQuery("#listTopADBaratas").jqGridOpenData({
                url:'AjustesDirectosService/top/',
                colNames:['Descricao', 'Pre&ccedil;o', 'Data Publica&ccedil;&atilde;o', 'Data contrato', 'Adjudicante', 'Contratado'],
                colModel:[
                    {name:'Descricao',index:'Descricao', width:200},
                    {name:'Preco',index:'Preco', width:80, align:"right", formatter:'currency'},
                    {name:'DataPublicacao',index:'DataPublicacao', width:70, formatter:'date'},
                    {name:'DataContrato',index:'DataContrato', width:65, formatter:'date'},
                    {name:'ea_Nome',index:'ea_Nome', width:150},
                    {name:'ec_Nome',index:'ec_Nome', width:150}
                ],
                sortname: "Preco",
                sortorder: "ASC",
                rowNum:50,
                hasHistory: false,
                toppager:false,
                loadonce: true,
                caption:""
            });
        }

        function TopEntidadesA(){
            jQuery("#listTopEntidadesA").jqGridOpenData({
                url:'EntidadesService/top/?tipo=A',
                colNames:['Nif', 'Nome Empresa', 'Nº', 'Total'],
                colModel:[
                    {name:'Nif',index:'Nif', width:50},
                    {name:'Nome',index:'Nome', width:140},
                    {name:'NumRegistos',index:'NumRegistos', width:35},
                    {name:'ValorTotal',index:'ValorTotal', width:70, align:"right", formatter:'currency'}
                ],
                sortname: "ValorTotal",
                sortorder: "DESC",
                rowNum:50,
                hasHistory: false,
                toppager:false,
                loadonce: true,
                caption:"Top Adjudicantes por Valor Total"
            });
            jQuery("#listTopEntidadesANum").jqGridOpenData({
                url:'EntidadesService/top/?tipo=A',
                colNames:['Nif', 'Nome Empresa', 'Nº', 'Total'],
                colModel:[
                    {name:'Nif',index:'Nif', width:50},
                    {name:'Nome',index:'Nome', width:140},
                    {name:'NumRegistos',index:'NumRegistos', width:35},
                    {name:'ValorTotal',index:'ValorTotal', width:70, align:"right", formatter:'currency'}
                ],
                sortname: "NumRegistos",
                sortorder: "DESC",
                rowNum:50,
                hasHistory: false,
                toppager:false,
                loadonce: true,
                caption:"Top Adjudicantes por Número"
            });
        }

        function TopEntidadesC(){
            jQuery("#listTopEntidadesC").jqGridOpenData({
                url:'EntidadesService/top/?tipo=C',
                colNames:['Nif', 'Nome Empresa', 'Nº', 'Total'],
                colModel:[
                    {name:'Nif',index:'Nif', width:50},
                    {name:'Nome',index:'Nome', width:140},
                    {name:'NumRegistos',index:'NumRegistos', width:35},
                    {name:'ValorTotal',index:'ValorTotal', width:70, align:"right", formatter:'currency'}
                ],
                sortname: "ValorTotal",
                sortorder: "DESC",
                rowNum:50,
                hasHistory: false,
                toppager:false,
                loadonce: true,
                caption:"Top Contratadas por Valor Total"
            });
            jQuery("#listTopEntidadesCNum").jqGridOpenData({
                url:'EntidadesService/top/?tipo=C',
                colNames:['Nif', 'Nome Empresa', 'Nº', 'Total'],
                colModel:[
                    {name:'Nif',index:'Nif', width:50},
                    {name:'Nome',index:'Nome', width:140},
                    {name:'NumRegistos',index:'NumRegistos', width:35},
                    {name:'ValorTotal',index:'ValorTotal', width:70, align:"right", formatter:'currency'}
                ],
                sortname: "NumRegistos",
                sortorder: "DESC",
                rowNum:50,
                hasHistory: false,
                toppager:false,
                loadonce: true,
                caption:"Top Contratadas por Número"
            });
        }

        google.load("visualization", "1", {packages:["corechart", 'annotatedtimeline']});
        function GraficoMensal(){
            //var data = new google.visualization.DataTable();

           // var gadgetHelper = new google.visualization.GadgetHelper();

            query = new google.visualization.Query('AjustesDirectosService/gvTotaisMesAno/?type=v');
            query.send(responseHandlerGraficoMensalValor);

            query2 = new google.visualization.Query('AjustesDirectosService/gvTotaisMesAno/?type=n');
            query2.send(responseHandlerGraficoMensalNum);
        }

        function responseHandlerGraficoMensalValor(response) {
            // Remove the loading message (if exists).
            //var loadingMsgContainer = document.getElementById('loading');
            //loadingMsgContainer.style.display = 'none';

            if (response.isError()) {
                alert('Error in query: ' + response.getMessage() + ' ' + response.getDetailedMessage());
                return;
            }

            var dataTable = response.getDataTable();
            var chart = new google.visualization.ColumnChart(document.getElementById('GraficoMensalValor_div'));
            chart.draw(dataTable, {width: 920, height: 480, title: 'Ajustes Directos Valor Total por mês',
                hAxis: {title: 'Mês', textStyle: {fontSize: 9}}, vAxis: {title: 'Valor Total', textStyle: {fontSize: 9}}, legend:'top'
            });
        };
        function responseHandlerGraficoMensalNum(response) {
            // Remove the loading message (if exists).
            //var loadingMsgContainer = document.getElementById('loading');
            //loadingMsgContainer.style.display = 'none';

            if (response.isError()) {
                alert('Error in query: ' + response.getMessage() + ' ' + response.getDetailedMessage());
                return;
            }

            var dataTable = response.getDataTable();
            var chart = new google.visualization.ColumnChart(document.getElementById('GraficoMensalNum_div'));
            chart.draw(dataTable, {width: 920, height: 480, title: 'Ajustes Directos Número Total por mês',
                hAxis: {title: 'Mês', textStyle: {fontSize: 9}}, vAxis: {title: 'Número', textStyle: {fontSize: 9}}, legend:'top'
            });
        };

        function GraficoTemporal(){
            query = new google.visualization.Query('AjustesDirectosService/gvTotaisDia');
            query.send(responseHandlerGraficoTemporalValor);

            //query2 = new google.visualization.Query('AdjudicacoesDirectasService/gvTotaisMesAno/?type=n');
            //query2.send(responseHandlerGraficoTemporalNum);
        }

        function responseHandlerGraficoTemporalValor(response) {
            // Remove the loading message (if exists).
            //var loadingMsgContainer = document.getElementById('loading');
            //loadingMsgContainer.style.display = 'none';

            if (response.isError()) {
                alert('Error in query: ' + response.getMessage() + ' ' + response.getDetailedMessage());
                return;
            }

            var d = new Date();
            var dataTable = response.getDataTable();
            var chart = new google.visualization.AnnotatedTimeLine(document.getElementById('GraficoTemporalValor_div'));
            chart.draw(dataTable, {displayExactValues:true, scaleType:'allmaximized', thickness:1, zoomStartTime: new Date(d.getFullYear(), 0, 1),displayAnnotations: true
            });
        };
    </script>

    <div id="tabsEstatisticas">
        <ul>
            <li><a>Top A.D. mais Caras</a></li>
            <li><a>Top A.D. mais Baratas</a></li>
            <li><a>Top Adjudicantes</a></li>
            <li><a>Top Contratadas</a></li>
            <li><a>Gráfico mensal</a></li>
            <li><a>Gráfico temporal</a></li>
        </ul>
        <div id="tabs-TopADCaras">
            <table id="listTopADCaras"></table>
        </div>
        <div id="tabs-TopADBaratas">
            <table id="listTopADBaratas"></table>
        </div>
        <div id="tabs-TopEntidadesA">
            <div style="float:left;width: 50%"><table id="listTopEntidadesA"></table></div>
            <div style="float:right;width: 49%"><table id="listTopEntidadesANum"></table></div>
            <div class="clear"></div>
        </div>
        <div id="tabs-TopEntidadesC">
            <div style="float:left;width: 50%"><table id="listTopEntidadesC"></table></div>
            <div style="float:right;width: 49%"><table id="listTopEntidadesCNum"></table></div>
            <div class="clear"></div>
        </div>
        <div id="tabs-GraficoMensal">
            <div class="Ajuda">Nota: os Ajustes directos sem data de contrato, n&atilde;o s&atilde;o contabilizados nos gr&aacute;ficos</div>
            <div id="GraficoMensalValor_div"></div>
            <div id="GraficoMensalNum_div"></div>
        </div>
        <div id="tabs-GraficoTemporal">
            <div class="Ajuda">Nota: os Ajustes directos sem data de contrato, n&atilde;o s&atilde;o contabilizados no gr&aacute;fico</div>
            <div id="GraficoTemporalValor_div" style="width:920px; height:600px"></div>
        </div>
    </div>

    <div class="Ajuda">Se encontrar algum erro ou incosistência por favor reporte-nos. </div>
</div>