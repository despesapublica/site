<div class="mainContent">
    <div class="typography">
        <% if Level(2) %>
        <% include BreadCrumbs %>
        <% end_if %>

        <h2>$Title</h2>

        <div class="AdjudicacaoDetalhe">
            <!-- AddThis Button BEGIN -->
            <div class="addthis_toolbox addthis_default_style ">
                <a class="addthis_counter"></a>
            </div>
            <script type="text/javascript">var addthis_config = {"data_track_clickback":false};</script>
            <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4d9661bf36f5be25"></script>
            <!-- AddThis Button END -->

            $Content
            <table border="0" width="90%">
                <tr>
                    <td>
                        <div id="Preco" class="item"><span class="label">Preço:</span><span class="valor">$detail.Preco.Nice</span></div>
                    </td>
                    <td>
                        <div id="NProcesso" class="item"><span class="label">N.º Procedimento: </span><span class="valor"><a href="$detail.Url" title="Ver o site oficial" target="_blank">$detail.NProcesso</a></span></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div id="DataContrato" class="item"><span class="label">Data Contrato:</span><span class="valor">$detail.DataContrato.Nice</span></div>
                    </td>
                    <td>
                        <div id="DataPublicacao" class="item"><span class="label">Data Publicação:</span><span class="valor">$detail.DataPublicacao.Nice</span></div>
                    </td>
                </tr>
            </table>

            <div id="Descricao" class="item">$detail.Descricao</div>

            <table border="0" width="100%">
                <tr>
                    <td width="50%" valign="top">
            <h3>Adjudicante(s)</h3>
            <% control adjudicantes %>
            <div class="Adjudicante" class="item"><a href="entidades/View?ID=$UId" title="Ver detalhe da empresa">$Nif - $Nome <% if DataCriacao %>({$DataCriacao}) <% end_if %></a></div>
            <% end_control %>
                    </td>
                    <td  valign="top">
            <h3>Contratado(s)</h3>
            <% control contratados %>
            <div class="Contratado" class="item"><a href="entidades/View?ID=$UId" title="Ver detalhe da empresa">$Nif - $Nome <% if DataCriacao %>({$DataCriacao}) <% end_if %></a></div>
            <% end_control %>
                    </td>
                </tr>
            </table>
            
            <div id="LocalExecucao" class="item"><span class="label">Local de execução:</span><span class="valor">$formatLocaisExecucao &nbsp;</span><div class="clear"></div></div>
            <div id="PrazoExecucao" class="item"><span class="label">Prazo de execução:</span><span class="valor">$detail.PrazoExecucao &nbsp;</span></div>
            <div id="Criterio" class="item"><span class="label">Critério:</span><span class="valor">$detail.Criterio &nbsp;</span></div>

            <div class="Ajuda">Se encontrar algum erro ou incosistência por favor reporte-nos, se tiver motivos para achar que este Ajuste Directo foi prejudicial para o país, deixe o seu comentário, carregue no botão "I don't like this page" e partilhe com os seus contactos. Juntos podemos fazer a diferença. </div>

            <div id="disqus_thread"></div>
            <script type="text/javascript">
                /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
                var disqus_shortname = 'dinheiropublico'; // required: replace example with your forum shortname

                // The following are highly recommended additional parameters. Remove the slashes in front to use.
                var disqus_identifier = 'ad_$detail.UId';
                //var disqus_url = '{$getDomain}{$Link}';
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
    </div>
</div>