jQuery(document).ready(function($) {
    var raceStatusTable = jQuery("#raceStatusTable");
    jQuery( "#start_fetch" ).click(function() {
        var fetch_url =   jQuery("#fetch_url").val();
        if(!fetch_url){
            jQuery("#UrlError").removeClass("hide");
            window.setTimeout(() => {
                jQuery("#UrlError").addClass("hide");
            }, 1000);
        }
        else {
            jQuery.get(fetch_url, function(response, status) {
                if(status === 'success'){
                    var tableData = [];
                    var dom_nodes = $($.parseHTML(response));
                    var tableRows = dom_nodes.find('#ergebnis tbody tr');
                    for (i = 0; i < tableRows.length; i++) { 
                        var currentRow = tableRows[i];
                        tableData[i] = {
                            "PL": jQuery(currentRow).find('td:eq(0)').text(),
                            "Name": jQuery(currentRow).find('td:eq(1)').text(),
                            "Nr": jQuery(currentRow).find('td:eq(2)').text(),
                            "Box": jQuery(currentRow).find('td:eq(3)').text(),
                            "Abstand": jQuery(currentRow).find('td:eq(4)').text(),
                            "Gewinn": jQuery(currentRow).find('td:eq(5)').text(),
                            "Besitzer": jQuery(currentRow).find('td:eq(6)').text(),
                            "Trainer": jQuery(currentRow).find('td:eq(7)').text(),
                            "Reiter": jQuery(currentRow).find('td:eq(8)').text(),
                            "Gew": jQuery(currentRow).find('td:eq(9)').text(),
                            "Quote": jQuery(currentRow).find('td:eq(10)').text(),
                          };
                      }

                      const tableDom = tableData.map((data, idx) => {
                          var PLId = data.PL.replace(/\./g,'');
                            return ('<div><div class="field"><label for="race_id">Pl Id</label><input type="number" name="race_id_'+idx+'" id="race_id" value="'+PLId+'"/></div><div class="field"><label for="race_name">Name</label><input type="text" name="race_name_'+idx+'" id="race_name" value="'+data.Name+'"/></div><div class="field"><label for="race_nr">Nr</label> <input type="text" name="race_nr_'+idx+'" id="race_nr" value="'+data.Nr+'"/></div><div class="field"><label for="race_box">Box</label><input type="text" name="race_box_'+idx+'" id="race_box" value="'+data.Box+'"/></div><div class="field"><label for="race_abstand">Abstand</label><input type="text" name="race_abstand_'+idx+'" id="race_abstand" value="'+data.Abstand+'"/></div><div class="field"><label for="race_gewinn">Gewinn</label><input type="text" name="race_gewinn_'+idx+'" id="race_gewinn" value="'+data.Gewinn+'"/></div><div class="field"><label for="race_besitzer">Besitzer</label><input type="text" name="race_besitzer_'+idx+'" id="race_besitzer" value="'+data.Besitzer+'"/></div><div class="field"><label for="race_trainer">Trainer</label><input type="text" name="race_trainer_'+idx+'" id="race_trainer" value="'+data.Trainer+'"/></div><div class="field"><label for="race_reiter">Reiter</label><input type="text" name="race_reiter_'+idx+'" id="race_reiter" value="'+data.Reiter+'"/></div><div class="field"><label for="race_gew">Gew</label><input type="text" name="race_gew_'+idx+'" id="race_gew" value="'+data.Gew+'"/></div><div class="field"><label for="race_quote">Quote</label><input type="text" name="race_quote_'+idx+'" id="race_quote" value="'+data.Quote+'"/></div></div>');
                      });
                      tableDom.push('<input type="hidden" name="totalRows" value="'+tableData.length+'" />');
                      raceStatusTable.empty();
                      raceStatusTable.append(tableDom);
                }
            });
        }
    });
});