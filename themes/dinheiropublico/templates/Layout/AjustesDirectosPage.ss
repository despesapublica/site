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
                colNames:['Descricao', 'Pre&ccedil;o', 'Data Publica&ccedil;&atilde;o', 'Data contrato', 'Adjudicante', 'Contratado'],
                colModel:[
                    {name:'Descricao',index:'Descricao', width:200},
                    {name:'Preco',index:'Preco', width:80, align:"right", formatter:'currency'},
                    {name:'DataPublicacao',index:'DataPublicacao', width:70, formatter:'date'},
                    {name:'DataContrato',index:'DataContrato', width:65, formatter:'date'},
                    {name:'ea_Nome',index:'ea_Nome', width:150},
                    {name:'ec_Nome',index:'ec_Nome', width:150}
                ],
                pager: jQuery('#pager2'),
                sortname: "DataPublicacao",
                sortorder: "Desc",
                caption:"Ajustes Directos"
            });

            $('#search-precoMin').inputmask('9 999 999 999,99 €', { 'numericInput': true, 'autounmask' : true});    //   123456  =>  € ___.__1.234,56
            $('#search-precoMax').inputmask('9 999 999 999,99 €', { 'numericInput': true, 'autounmask' : true});    //   123456  =>  € ___.__1.234,56

            var dates = $( "#search-dataContratoMin, #search-dataContratoMax" ).datepicker({
                defaultDate: "-2m",
                numberOfMonths: 3,
                dateFormat: 'dd-mm-yy',
                onSelect: function( selectedDate ) {
                    var option = this.id == "search-dataContratoMin" ? "minDate" : "maxDate",
                        instance = $( this ).data( "datepicker" ),
                        date = $.datepicker.parseDate(
                            instance.settings.dateFormat ||
                            $.datepicker._defaults.dateFormat,
                            selectedDate, instance.settings );
                    dates.not( this ).datepicker( "option", option, date );
                }
            });
            var dates = $( "#search-dataPublicacaoMin, #search-dataPublicacaoMax" ).datepicker({
                defaultDate: "-2m",
                numberOfMonths: 3,
                dateFormat: 'dd-mm-yy',
                onSelect: function( selectedDate ) {
                    var option = this.id == "search-dataPublicacaoMin" ? "minDate" : "maxDate",
                        instance = $( this ).data( "datepicker" ),
                        date = $.datepicker.parseDate(
                            instance.settings.dateFormat ||
                            $.datepicker._defaults.dateFormat,
                            selectedDate, instance.settings );
                    dates.not( this ).datepicker( "option", option, date );
                }
            });
        });


        function validate_form(){
            $('#search-precoMin').inputmask({'mask':'999999999,99', 'numericInput': true, 'autounmask' : true, 'placeholder':'' });
            $('#search-precoMax').inputmask({'mask':'999999999,99', 'numericInput': true, 'autounmask' : true, 'placeholder':'' });
        }
    </script>

    <div id="mysearch"></div>

    <div class="pesquisaBox" class="ui-widget ui-helper-reset">
        <h3 class="ui-helper-reset ui-state-default ui-corner-top">
            <span class="ui-icon ui-icon-search"></span><span class="title">Pesquisa</span></h3>
        <div class="ui-helper-reset ui-widget-content ui-corner-bottom">
            <form method="get" action="$Link" name="search-form" id="search-form" onsubmit="return validate_form(this)">
                <table width="100%" cellpadding="2">
                    <tr>
                        <td class="label"><label for="search-keywords">Procurar por </label></td>
                        <td><input type="text" name="sk" id="search-keywords" value="$searchParams.sk" size="50" /></td>
                    </tr>
                    <tr>
                        <td class="label"><label for="search-dataContratoMin">Data Contrato de </label></td>
                        <td>
                            <input type="text" name="dataContratoMin" id="search-dataContratoMin" value="$searchParams.dataContratoMin.Nice" size="15" />
a
                            <input type="text" name="dataContratoMax" id="search-dataContratoMax" value="$searchParams.dataContratoMax.Nice" size="15" />
                        </td>
                    </tr>
                    <tr>
                        <td class="label"><label for="search-dataPublicacaoMin">Data Publica&ccedil;&atilde;o de </label></td>
                        <td>
                            <input type="text" name="dataPublicacaoMin" id="search-dataPublicacaoMin" value="$searchParams.dataPublicacaoMin.Nice" size="15" />
a
                            <input type="text" name="dataPublicacaoMax" id="search-dataPublicacaoMax" value="$searchParams.dataPublicacaoMax.Nice" size="15" />
                        </td>
                    </tr>
                    <tr>
                        <td class="label"><label for="search-precoMin">Preço de </label></td>
                        <td>
                            <input type="text" name="precoMin" id="search-precoMin" value="$searchParams.precoMin.NiceAmountOnly" size="15" />
a
                            <input type="text" name="precoMax" id="search-precoMax" value="$searchParams.precoMax.NiceAmountOnly" size="15" />
                        </td>
                    </tr>
                </table>
                <div class="buttons">
                    <input type="submit" value="Pesquisar" />
                </div>
            </form>
        </div>
    </div>

    <div class="grid">
        <table id="list2"></table>
        <div id="pager2"></div>
    </div>
</div>