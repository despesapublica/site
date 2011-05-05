<div class="mainContent">
    <div class="typography">
        <% if Level(2) %>
        <% include BreadCrumbs %>
        <% end_if %>

        <h2>$Title</h2>

        <script type='text/javascript'>
            jQuery(document).ready(function(){
                var tabs = jQuery("#tabsEntidade li a");
                jQuery(tabs[0]).attr("href", "#tabs-Adjudicacoes");
                jQuery(tabs[1]).attr("href", "#tabs-Contratados");
                jQuery(tabs[2]).attr("href", "#tabs-Adjudicantes");
                jQuery(tabs[3]).attr("href", "#tabs-GraficoMensal");
                jQuery(tabs[4]).attr("href", "#tabs-GraficoTemporal");

                GraficoPequenoMensal();
            });
        </script>

        <div class="EntidadeDetalhe">
            <!-- AddThis Button BEGIN -->
            <div class="addthis_toolbox addthis_default_style ">
                <a class="addthis_counter"></a>
            </div>
            <script type="text/javascript">var addthis_config = {"data_track_clickback":false};</script>
            <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4d9661bf36f5be25"></script>
            <!-- AddThis Button END -->

            <div class="EntidadeDetalheEsq">
                $Content

                <% control totalAD %>
                <% if NumRegistosAdAdjudicadas %>
                <div id="ValorAdjudicacoes" class="item"><span class="label">Total Gasto:</span><span class="valor"> $ValorTotalAdAdjudicadas.Nice</span></div>
                <div id="NAdjudicacoes" class="item"><span class="label">Nº A.D. Contratadas</span><span class="valor"> $NumRegistosAdAdjudicadas.Nice</span></div>
                <% end_if %>
                <% if NumRegistosAdContratadas %>
                <div id="ValorContratadas" class="item"><span class="label">Total Recebido:</span><span class="valor"> $ValorTotalAdContratadas.Nice</span></div>
                <div id="NContratadas" class="item"><span class="label">Nº A.D. Ganhas:</span><span class="valor"> $NumRegistosAdContratadas.Nice</span></div>
                <% end_if %>
                <% end_control %>

                <div id="Nif" class="item"><span class="label">Nif:</span><span class="valor">$detail.Nif</span><div class="clear"></div></div>
                <div id="DataCriacao" class="item"><span class="label">Data Criação:</span><span class="valor">$detail.DataCriacao &nbsp;</span></div>
                <div class="consultaMJ">Consulte por mais informação sobre a empresa (sócios, sede, capital social, ...) no site do <a href="http://publicacoes.mj.pt/Pesquisa.aspx" target="_blank">Ministério da Justiça</a>. </div>

                <h3>Totais por ano</h3>
                <table border="0" width="100%" class="totais">
                    <tr class="ui-widget-header ui-state-default">
                        <th>
                            Ano
                        </th>
                        <% if numRegistosAdAdjudicadas %>
                        <th>
                            Total Gasto
                        </th>
                        <th>
                            Nº A.D. Contratadas
                        </th>
                        <% end_if %>
                        <% if numRegistosAdContratadas %>
                        <th>
                            Total Recebido
                        </th>
                        <th>
                            Nº A.D. Ganhas
                        </th>
                        <% end_if %>
                    </tr>
                    <% control estatisticas %>
                    <% if Ano != -1 %>
                    <tr>
                        <td>
                            <% if Ano == 0 %>
                            Sem data de contrato
                            <% else %>
                            $Ano
                            <% end_if %>
                        </td>
                        <% if NumRegistosAdAdjudicadas %>
                        <td>
                            $ValorTotalAdAdjudicadas.Nice
                        </td>
                        <td>
                            $NumRegistosAdAdjudicadas.Nice
                        </td>
                        <% end_if %>
                        <% if NumRegistosAdContratadas %>
                        <td>
                            $ValorTotalAdContratadas.Nice
                        </td>
                        <td>
                            $NumRegistosAdContratadas.Nice
                        </td>
                        <% end_if %>
                    </tr>
                    <% end_if %>
                    <% end_control %>
                </table>
            </div>
            <div class="EntidadeDetalheDir" style="margin-top:70px;">
                <div id="GraficoPequenoMensalValor_div"></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>

    <div id="tabsEntidade">
        <ul>
            <li><a>Adjudicações</a></li>
            <% if numRegistosAdAdjudicadas %>
            <li><a>Contratados</a></li>
            <% else %>
            <li style="display:none;"><a>Contratados</a></li>
            <% end_if %>
            <% if numRegistosAdContratadas %>
            <li><a>Adjudicantes</a></li>
            <% else %>
            <li style="display:none;"><a>Adjudicantes</a></li>
            <% end_if %>
            <li><a>Gráfico Mensal</a></li>
            <li><a>Gráfico Temporal</a></li>
        </ul>
        <div id="tabs-Adjudicacoes">
            <table id="list2"></table>
            <div id="pager2"></div>
        </div>
        <div id="tabs-Contratados">
            <p class="desc">Empresas que foram contratadas por esta entidade para prestarem serviços. Os valores apresentados correspondem só aos serviços prestados para esta entidade.</p>
            <table id="listContratados"></table>
            <div id="pagerContratados"></div>
        </div>
        <div id="tabs-Adjudicantes">
            <p class="desc">Entidades que contrataram serviços a esta empresa. Os valores apresentados correspondem só aos serviços contratados a esta empresa.</p>
            <table id="listAdjudicantes"></table>
            <div id="pagerAdjudicantes"></div>
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

    <div class="Ajuda">Se encontrar algum erro ou incosistência por favor reporte-nos, se tiver motivos para achar que esta Entidade lesou o país, deixe o seu comentário, carregue no botão "I don't like this page" e partilhe com os seus contactos. Juntos podemos fazer a diferença. </div>

    <div id="disqus_thread"></div>
    <script type="text/javascript">
        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
        var disqus_shortname = 'dinheiropublico'; // required: replace example with your forum shortname

        // The following are highly recommended additional parameters. Remove the slashes in front to use.
        var disqus_identifier = 'emp_$detail.UId';
//        var disqus_url = '{$getDomain}{$Link}';
		var disqus_url = 'http://despesapublica.com{$Link}';
        var disqus_developer = 0; // developer mode is on

        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function() {
            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
            dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
    </script>
    <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
</div>


    <script type="text/javascript">
        google.load("visualization", "1", {packages:["corechart",'annotatedtimeline']});
        function GraficoPequenoMensal(){
            query = new google.visualization.Query('AjustesDirectosService/gvTotaisMesAno/?type=v&idEntidade={$detail.UId}');
            query.send(responseHandlerGraficoPequenoMensalValor);
        }

        function responseHandlerGraficoPequenoMensalValor(response) {
            if (response.isError()) {
                alert('Error in query: ' + response.getMessage() + ' ' + response.getDetailedMessage());
                return;
            }

            var dataTable = response.getDataTable();
      	if(dataTable.getNumberOfRows() > 0)
        {
            var chart = new google.visualization.ColumnChart(document.getElementById('GraficoPequenoMensalValor_div'));
            chart.draw(dataTable, {width: 410, height: 320, title: 'Ajustes Directos Valor Total por mês',
                legend:'top', 
                vAxis:{textPosition:'in',textStyle:{fontSize:9}},
                hAxis:{textStyle:{fontSize:9}},
                chartArea:{width:"100%"}
            });
        }
        };
        function GraficoMensal(){
            query = new google.visualization.Query('AjustesDirectosService/gvTotaisMesAno/?type=v&idEntidade={$detail.UId}');
            query.send(responseHandlerGraficoMensalValor);

            query2 = new google.visualization.Query('AjustesDirectosService/gvTotaisMesAno/?type=n&idEntidade={$detail.UId}');
            query2.send(responseHandlerGraficoMensalNum);
        }

        function responseHandlerGraficoMensalValor(response) {
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
            query = new google.visualization.Query('AjustesDirectosService/gvTotaisDia?idEntidade={$detail.UId}');
            query.send(responseHandlerGraficoTemporalValor);
        }

        function responseHandlerGraficoTemporalValor(response) {
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

    <script type='text/javascript'>
        jQuery(document).ready(function(){
            var initialized = [false,false,false,false,false,false,false];
            $('#tabsEntidade').tabs
            (
            {
                show: function(event, ui)
                {
                    if(ui.index == 0 && !initialized[0])
                    {
                        listAD();
                        initialized[0] = true;
                    }
                    else if(ui.index == 1 && !initialized[1])
                    {
                        listaContratados();
                        initialized[1] = true;
                    }
                    else if(ui.index == 2 && !initialized[2])
                    {
                        listaAdjudicantes();
                        initialized[2] = true;
                    }
                    else if(ui.index == 3 && !initialized[3])
                    {
                        GraficoMensal();
                        initialized[3] = true;
                    }
                    else if(ui.index == 4 && !initialized[4])
                    {
                        GraficoTemporal();
                        initialized[4] = true;
                    }
                }
            }
        );
        });

        function listAD(){
            jQuery("#list2").jqGridOpenData({
                url:'$gridADLinkRequest',
                colNames:['Descricao', 'Pre&ccedil;o', 'Data Publica&ccedil;&atilde;o', 'Data contrato', 'Entidade'],
                colModel:[
                    {name:'Descricao',index:'Descricao', width:200},
                    {name:'Preco',index:'Preco', width:60, align:"right", formatter:'currency'},
                    {name:'DataPublicacao',index:'DataPublicacao', width:60, formatter:'date'},
                    {name:'DataContrato',index:'DataContrato', width:60, formatter:'date'},
                        <% if numRegistosAdAdjudicadas %>
                        {name:'ea_Nome',index:'ea_Nome', width:170}
                        <% else %>
                        {name:'ec_Nome',index:'ec_Nome', width:170}
                        <% end_if %>
                    ],
                pager: jQuery('#pager2'),
                sortname: "DataPublicacao",
                sortorder: "Desc",
                hasHistory: false,
                caption:""
            });
        }

        function listaContratados(){
            jQuery("#listContratados").jqGridOpenData({
                url:'$gridEntidadesContratadasLinkRequest',
                colNames:['Nif', 'Nome', 'Data criação', 'Total', 'Valor total', 'Valor Max.', 'Última'],
                colModel:[
                    {name:'Nif',index:'Nif', width:60},
                    {name:'Nome',index:'Nome', width:200},
                    {name:'DataCriacao',index:'DataCriacao', width:60, formatter:'date'},
                    {name:'Total',index:'Total', width:60},
                    {name:'ValorTotal',index:'ValorTotal', width:60, align:"right", formatter:'currency'},
                    {name:'ValorMax',index:'ValorMax', width:60, align:"right", formatter:'currency'},
                    {name:'DataMax',index:'DataMax', width:60, formatter:'date'}
                ],
                pager: jQuery('#pagerContratadas'),
                sortname: "DataMax",
                sortorder: "Desc",
                hasHistory: false,
                caption:"",
                gridview: false,
                subGrid : true,
                subGridRowExpanded: showSubgridContratados
            });
        }

        function listaAdjudicantes(){
            jQuery("#listAdjudicantes").jqGridOpenData({
                url:'$gridEntidadesAdjudicantesLinkRequest',
                colNames:['Nif', 'Nome', 'Data criação', 'Total', 'Valor total', 'Valor Max.', 'Última'],
                colModel:[
                    {name:'Nif',index:'Nif', width:60},
                    {name:'Nome',index:'Nome', width:200},
                    {name:'DataCriacao',index:'DataCriacao', width:60, formatter:'date'},
                    {name:'Total',index:'Total', width:60},
                    {name:'ValorTotal',index:'ValorTotal', width:60, align:"right", formatter:'currency'},
                    {name:'ValorMax',index:'ValorMax', width:60, align:"right", formatter:'currency'},
                    {name:'DataMax',index:'DataMax', width:60, formatter:'date'}
                ],
                pager: jQuery('#pagerAdjudicantes'),
                sortname: "DataMax",
                sortorder: "Desc",
                hasHistory: false,
                caption:"",
                gridview: false,
                subGrid : true,
                subGridRowExpanded: showSubgridAdjudicantes
            });
        }

        function showSubgridContratados(subgrid_id, row_id) {
            var subgrid_table_id, pager_id, profundidade;
            subgrid_table_id = subgrid_id+"_t";
            pager_id = "p_"+subgrid_table_id;
            //profundidade = numOcorrencias(subgrid_table_id, "_t_")+1;
            $("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");

            jQuery("#"+subgrid_table_id).jqGrid({
                url:"AjustesDirectosService/pesquisa/?tipoRelacao=A&idEntidadeRelacionada={$detail.UId}&idEntidade="+row_id,
                datatype: "json",
                colNames:['Descricao', 'Pre&ccedil;o', 'Data Publica&ccedil;&atilde;o', 'Data contrato'],
                colModel:[
                    {name:'Descricao',index:'Descricao', width:200},
                    {name:'Preco',index:'Preco', width:60, align:"right", formatter:'currency'},
                    {name:'DataPublicacao',index:'DataPublicacao', width:60, formatter:'date'},
                    {name:'DataContrato',index:'DataContrato', width:60, formatter:'date'}
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

        function showSubgridAdjudicantes(subgrid_id, row_id) {
            var subgrid_table_id, pager_id, profundidade;
            subgrid_table_id = subgrid_id+"_t";
            pager_id = "p_"+subgrid_table_id;
            //profundidade = numOcorrencias(subgrid_table_id, "_t_")+1;
            $("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");

                          jQuery("#"+subgrid_table_id).jqGrid({
                          url:"AjustesDirectosService/pesquisa/?tipoRelacao=C&idEntidadeRelacionada={$detail.UId}&idEntidade="+row_id,
                datatype: "json",
                colNames:['Descricao', 'Pre&ccedil;o', 'Data Publica&ccedil;&atilde;o', 'Data contrato'],
                colModel:[
                    {name:'Descricao',index:'Descricao', width:200},
                    {name:'Preco',index:'Preco', width:60, align:"right", formatter:'currency'},
                    {name:'DataPublicacao',index:'DataPublicacao', width:60, formatter:'date'},
                    {name:'DataContrato',index:'DataContrato', width:60, formatter:'date'}
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
    </script>