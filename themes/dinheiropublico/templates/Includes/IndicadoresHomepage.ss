<script type='text/javascript'>
	 jQuery(document).ready(function(){
		var tabs = jQuery("#tabsIndicadores li a");
        jQuery(tabs[0]).attr("href", "#tabs-Dia");
        jQuery(tabs[1]).attr("href", "#tabs-Sem");
        jQuery(tabs[2]).attr("href", "#tabs-Mes");
        jQuery(tabs[3]).attr("href", "#tabs-Ano");
        jQuery(tabs[4]).attr("href", "#tabs-Total");
        jQuery( "#tabsIndicadores" ).tabs();
	});
</script>

<div id="tabsIndicadores">
    <ul>
        <li><a>Ontem</a></li>
        <li><a>Sem.</a></li>
        <li><a>Mês</a></li>
        <li><a>Ano</a></li>
        <li><a>Total</a></li>
    </ul>
    <div id="tabs-Dia">
        <div style="font-style: italic;line-height: 1.2em;padding-bottom: 7px;">Os valores aqui apresentados, tem em conta a data de publicação. Nos restantes separadores tem em conta a data de contrato.</div>
        <table class="rowAlternate" width="100%">
            <tr>
                <td class="label">N&ordm; Ajustes Directos:</td>
                <td class="valor">$indicadoresOntem.NumRegistos.Nice</td>
            </tr>
            <tr>
                <td class="label">Valor Total:</td>
                <td class="valor">$indicadoresOntem.PrecoTotal.Nice</td>
            </tr>
            <tr>
                <td class="label">Valor M&eacute;dio:</td>
                <td class="valor">$indicadoresOntem.PrecoMedio.Nice</td>
            </tr>
            <tr>
                <td class="label">Valor M&aacute;ximo:</td>
                <td class="valor">$indicadoresOntem.PrecoMax.Nice</td>
            </tr>
            <tr>
                <td class="label">Valor M&iacute;nimo:</td>
                <td class="valor">$indicadoresOntem.PrecoMin.Nice</td>
            </tr>
            <tr>
                <td class="label">Nº Adjudicantes:</td>
                <td class="valor">$indicadoresOntem.NumRegistoAdjudicantes.Nice</td>
            </tr>
            <tr>
                <td class="label">Nº Contratadas:</td>
                <td class="valor">$indicadoresOntem.NumRegistoContratados.Nice</td>
            </tr>
            <tr>
                <td class="label">Data &uacute;ltimo Contrato:</td>
                <td class="valor">$indicadoresOntem.DataContratoMax.Nice</td>
            </tr>
            <tr>
                <td class="label">Data primeiro Contrato:</td>
                <td class="valor">$indicadoresOntem.DataContratoMin.Nice</td>
            </tr>
        </table>
    </div>
    <div id="tabs-Sem">
        <table class="rowAlternate" width="100%">
            <tr>
                <td class="label">N&ordm; Ajustes Directos:</td>
                <td class="valor">$indicadoresSemana.NumRegistos.Nice</td>
            </tr>
            <tr>
                <td class="label">Valor Total:</td>
                <td class="valor">$indicadoresSemana.PrecoTotal.Nice</td>
            </tr>
            <tr>
                <td class="label">Valor M&eacute;dio:</td>
                <td class="valor">$indicadoresSemana.PrecoMedio.Nice</td>
            </tr>
            <tr>
                <td class="label">Valor M&aacute;ximo:</td>
                <td class="valor">$indicadoresSemana.PrecoMax.Nice</td>
            </tr>
            <tr>
                <td class="label">Valor M&iacute;nimo:</td>
                <td class="valor">$indicadoresSemana.PrecoMin.Nice</td>
            </tr>
            <tr>
                <td class="label">Nº Adjudicantes:</td>
                <td class="valor">$indicadoresSemana.NumRegistoAdjudicantes.Nice</td>
            </tr>
            <tr>
                <td class="label">Nº Contratadas:</td>
                <td class="valor">$indicadoresSemana.NumRegistoContratados.Nice</td>
            </tr>
            <!--tr>
                <td class="label">Tempo Médio Publicação:</td>
                <td class="valor">$indicadoresSemana.NDatasDiffMedio</td>
            </tr-->
            <tr>
                <td class="label">Data &uacute;ltima Publica&ccedil;&atilde;o:</td>
                <td class="valor">$indicadoresSemana.DataContratoMax.Nice</td>
            </tr>
            <tr>
                <td class="label">Data primeira Publica&ccedil;&atilde;o:</td>
                <td class="valor">$indicadoresSemana.DataPublicacaoMin.Nice</td>
            </tr>
            <tr>
                <td class="label">Data &uacute;ltimo Contrato:</td>
                <td class="valor">$indicadoresSemana.DataContratoMax.Nice</td>
            </tr>
            <tr>
                <td class="label">Data primeiro Contrato:</td>
                <td class="valor">$indicadoresSemana.DataContratoMin.Nice</td>
            </tr>
        </table>
    </div>
    <div id="tabs-Mes">
        <table class="rowAlternate" width="100%">
            <tr>
                <td class="label">N&ordm; Ajustes Directos:</td>
                <td class="valor">$indicadoresMes.NumRegistos.Nice</td>
            </tr>
            <tr>
                <td class="label">Valor Total:</td>
                <td class="valor">$indicadoresMes.PrecoTotal.Nice</td>
            </tr>
            <tr>
                <td class="label">Valor M&eacute;dio:</td>
                <td class="valor">$indicadoresMes.PrecoMedio.Nice</td>
            </tr>
            <tr>
                <td class="label">Valor M&aacute;ximo:</td>
                <td class="valor">$indicadoresMes.PrecoMax.Nice</td>
            </tr>
            <tr>
                <td class="label">Valor M&iacute;nimo:</td>
                <td class="valor">$indicadoresMes.PrecoMin.Nice</td>
            </tr>
            <tr>
                <td class="label">Nº Adjudicantes:</td>
                <td class="valor">$indicadoresMes.NumRegistoAdjudicantes.Nice</td>
            </tr>
            <tr>
                <td class="label">Nº Contratadas:</td>
                <td class="valor">$indicadoresMes.NumRegistoContratados.Nice</td>
            </tr>
            <!--tr>
                <td class="label">Tempo Médio Publicação:</td>
                <td class="valor">$indicadoresMes.NDatasDiffMedio</td>
            </tr-->
            <tr>
                <td class="label">Data &uacute;ltima Publica&ccedil;&atilde;o:</td>
                <td class="valor">$indicadoresMes.DataContratoMax.Nice</td>
            </tr>
            <tr>
                <td class="label">Data primeira Publica&ccedil;&atilde;o:</td>
                <td class="valor">$indicadoresMes.DataPublicacaoMin.Nice</td>
            </tr>
            <tr>
                <td class="label">Data &uacute;ltimo Contrato:</td>
                <td class="valor">$indicadoresMes.DataContratoMax.Nice</td>
            </tr>
            <tr>
                <td class="label">Data primeiro Contrato:</td>
                <td class="valor">$indicadoresMes.DataContratoMin.Nice</td>
            </tr>
        </table>
    </div>
    <div id="tabs-Ano">
         <table class="rowAlternate" width="100%">
            <tr>
                <td class="label">N&ordm; Ajustes Directos:</td>
                <td class="valor">$indicadoresAno.NumRegistos.Nice</td>
            </tr>
            <tr>
                <td class="label">Valor Total:</td>
                <td class="valor">$indicadoresAno.PrecoTotal.Nice</td>
            </tr>
            <tr>
                <td class="label">Valor M&eacute;dio:</td>
                <td class="valor">$indicadoresAno.PrecoMedio.Nice</td>
            </tr>
            <tr>
                <td class="label">Valor M&aacute;ximo:</td>
                <td class="valor">$indicadoresAno.PrecoMax.Nice</td>
            </tr>
            <tr>
                <td class="label">Valor M&iacute;nimo:</td>
                <td class="valor">$indicadoresAno.PrecoMin.Nice</td>
            </tr>
            <tr>
                <td class="label">Nº Adjudicantes:</td>
                <td class="valor">$indicadoresAno.NumRegistoAdjudicantes.Nice</td>
            </tr>
            <tr>
                <td class="label">Nº Contratadas:</td>
                <td class="valor">$indicadoresAno.NumRegistoContratados.Nice</td>
            </tr>
            <!--tr>
                <td class="label">Tempo Médio Publicação:</td>
                <td class="valor">$indicadoresAno.NDatasDiffMedio</td>
            </tr-->
            <tr>
                <td class="label">Data &uacute;ltima Publica&ccedil;&atilde;o:</td>
                <td class="valor">$indicadoresAno.DataContratoMax.Nice</td>
            </tr>
            <tr>
                <td class="label">Data primeira Publica&ccedil;&atilde;o:</td>
                <td class="valor">$indicadoresAno.DataPublicacaoMin.Nice</td>
            </tr>
            <tr>
                <td class="label">Data &uacute;ltimo Contrato:</td>
                <td class="valor">$indicadoresAno.DataContratoMax.Nice</td>
            </tr>
            <tr>
                <td class="label">Data primeiro Contrato:</td>
                <td class="valor">$indicadoresAno.DataContratoMin.Nice</td>
            </tr>
        </table>
    </div>
    <div id="tabs-Total">
        <table class="rowAlternate" width="100%">
            <tr>
                <td class="label">N&ordm; Ajustes Directos:</td>
                <td class="valor">$indicadoresTotais.NumRegistos.Nice</td>
            </tr>
            <tr>
                <td class="label">Valor Total:</td>
                <td class="valor">$indicadoresTotais.PrecoTotal.Nice</td>
            </tr>
            <tr>
                <td class="label">Valor M&eacute;dio:</td>
                <td class="valor">$indicadoresPrecoMaior0.PrecoMedio.Nice</td>
            </tr>
            <tr>
                <td class="label">Valor M&aacute;ximo:</td>
                <td class="valor">$indicadoresTotais.PrecoMax.Nice</td>
            </tr>
            <tr>
                <td class="label">Valor M&iacute;nimo:</td>
                <td class="valor">$indicadoresTotais.PrecoMin.Nice</td>
            </tr>
            <tr>
                <td class="label">Nº Adjudicantes:</td>
                <td class="valor">$indicadoresTotais.NumRegistoAdjudicantes.Nice</td>
            </tr>
            <tr>
                <td class="label">Nº Contratadas:</td>
                <td class="valor">$indicadoresTotais.NumRegistoContratados.Nice</td>
            </tr>
            <!--tr>
                <td class="label">Tempo Médio Publicação:</td>
                <td class="valor">$indicadoresTotais.NDatasDiffMedio</td>
            </tr-->
            <tr>
                <td class="label">Data &uacute;ltima Publica&ccedil;&atilde;o:</td>
                <td class="valor">$indicadoresTotais.DataContratoMax.Nice</td>
            </tr>
            <tr>
                <td class="label">Data primeira Publica&ccedil;&atilde;o:</td>
                <td class="valor">$indicadoresTotais.DataPublicacaoMin.Nice</td>
            </tr>
            <tr>
                <td class="label">Data &uacute;ltimo Contrato:</td>
                <td class="valor">$indicadoresTotais.DataContratoMax.Nice</td>
            </tr>
            <tr>
                <td class="label">Data primeiro Contrato:</td>
                <td class="valor">$indicadoresTotais.DataContratoMin.Nice</td>
            </tr>
            <tr>
                <td class="label">N&ordm; Ajustes &lt; 5&euro;:</td>
                <td class="valor">$indicadoresPrecosBaixo.NumRegistos.Nice</td>
            </tr>
            <tr>
                <td class="label">N&ordm; Ajustes sem data contrato:</td>
                <td class="valor">$indicadoresTotais.NumRegistosSemDataContrato.Nice</td>
            </tr>
        </table>
    </div>
</div>