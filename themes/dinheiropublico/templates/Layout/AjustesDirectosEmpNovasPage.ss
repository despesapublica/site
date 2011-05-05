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

		$Content

    </div>

    <script type='text/javascript'>
        jQuery(document).ready(function(){
            jQuery("#list2").jqGridOpenData({
			url:'$gridLinkRequest',
			colNames:['Descricao', 'Pre&ccedil;o', 'Data Publica&ccedil;&atilde;o', 'Data contrato', 'Dif Dias P.', 'Dif Dias C.', 'Data Cria&ccedil;&atilde;o Emp.', 'Contratado', 'Adjudicante'],
			colModel:[
				{name:'Descricao',index:'Descricao', width:180},
				{name:'Preco',index:'Preco', width:65, align:"right", formatter:'currency'},
				{name:'DataPublicacao',index:'DataPublicacao', width:65, formatter:'date'},
				{name:'DataContrato',index:'DataContrato', width:65, formatter:'date'},
				{name:'DiasP',index:'DiasP', width:25},
				{name:'DiasC',index:'DiasC', width:25},
				{name:'DataCriacao',index:'DataCriacao', width:65, formatter:'date'},
				{name:'ea_Nome',index:'ea_Nome', width:140},
				{name:'ec_Nome',index:'ec_Nome', width:140}
			],
			pager: jQuery('#pager2'),
			sortname: "DataPublicacao",
			sortorder: "Desc",
			caption:"Ajustes Directos a Empresas Novas"
		});

            /*$('#search-precoMin').inputmask('9 999 999 999,99 €', { 'numericInput': true, 'autounmask' : true});    //   123456  =>  € ___.__1.234,56
            $('#search-precoMax').inputmask('9 999 999 999,99 €', { 'numericInput': true, 'autounmask' : true});    //   123456  =>  € ___.__1.234,56

            var dates = $( "#search-dataMin, #search-dataMax" ).datepicker({
                defaultDate: "-2m",
                numberOfMonths: 3,
                dateFormat: 'dd-mm-yy',
                onSelect: function( selectedDate ) {
                    var option = this.id == "search-dataMin" ? "minDate" : "maxDate",
                        instance = $( this ).data( "datepicker" ),
                        date = $.datepicker.parseDate(
                            instance.settings.dateFormat ||
                            $.datepicker._defaults.dateFormat,
                            selectedDate, instance.settings );
                    dates.not( this ).datepicker( "option", option, date );
                }
            });*/

             jQuery( "#difDatasSlider" ).slider({
                min: 1,
                max: 48,
                range: "min",
                value: jQuery("#difDatas").val(),
                slide: function( event, ui ) {
                    jQuery( "#difDatasLabel" ).html( formatDifDatas(ui.value) );
                    jQuery( "#difDatas" ).val( ui.value);
                }
            });
            jQuery( "#difDatasLabel" ).html( formatDifDatas(jQuery("#difDatas").val()));
        });

        function formatDifDatas(value){
            if(!value)
                return "";
            if(value<12)
                return (value==1)?"1 M&ecirc;s":value+" Meses";

            var result = "";
            var anos = Math.floor(value/12);
            var meses = (value-anos*12);
            
            result = (anos==1)?"1 Ano":anos+" Anos";

            if(value%12==0)
                return result;
            
            return result + " e " + ((meses==1)?"1 M&ecirc;s":meses+" Meses");
        }

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
                        <td class="label"><label for="difDatas">Diferenças entre datas </label></td>
                        <td>
                            <input type="hidden" name="difDatas" id="difDatas" value="$searchParams.difDatas" />
                            <div id="difDatasSlider" style="width:200px"></div>
                            <div id="difDatasLabel"></div>
                        </td>
                    </tr>
                    <!--tr>
                        <td class="label"><label for="search-dataMin">Data de </label></td>
                        <td>
                            <input type="text" name="dataMin" id="search-dataMin" value="$searchParams.dataMin.Nice" size="15" />
a
                            <input type="text" name="dataMax" id="search-dataMax" value="$searchParams.dataMax.Nice" size="15" />
                        </td>
                    </tr>
                    <tr>
                        <td class="label"><label for="search-precoMin">Preço de </label></td>
                        <td>
                            <input type="text" name="precoMin" id="search-precoMin" value="$searchParams.precoMin.NiceAmountOnly" size="15" />
a
                            <input type="text" name="precoMax" id="search-precoMax" value="$searchParams.precoMax.NiceAmountOnly" size="15" />
                        </td>
                    </tr-->
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