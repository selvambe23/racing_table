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
                            "N": jQuery(currentRow).find('td:eq(0)').text(),
                            "Horse": jQuery(currentRow).find('td:eq(1)').text(),
                            "Sire": jQuery(currentRow).find('td:eq(2)').text(),
                            "Draw": jQuery(currentRow).find('td:eq(3)').text(),
                            "Colors": jQuery(currentRow).find('td:eq(4)').text(),
                            "Owner": jQuery(currentRow).find('td:eq(5)').text(),
                            "Trainer": jQuery(currentRow).find('td:eq(6)').text(),
                            "Jockey": jQuery(currentRow).find('td:eq(7)').text(),
                            "Weight": jQuery(currentRow).find('td:eq(8)').text(),
                            "Earnings": jQuery(currentRow).find('td:eq(9)').text(),
                            "Form": jQuery(currentRow).find('td:eq(10)').text(),
                            "Rating": jQuery(currentRow).find('td:eq(11)').text(),
                            "Blink": jQuery(currentRow).find('td:eq(12)').text(),
                            "Coupled": jQuery(currentRow).find('td:eq(13)').text(),
                            "Claim": jQuery(currentRow).find('td:eq(14)').text(),
                            "Breeders": jQuery(currentRow).find('td:eq(15)').text(),
                          };
                      }

                      const tableDom = tableData.map((data, idx) => {
                            return ('<div><div class="field"><label for="race_id">N°</label><input type="number" name="race_id_'+idx+'" id="race_id" value="'+data.N+'"/></div><div class="field"><label for="horse_name">Horse Name</label><input type="text" name="horse_name_'+idx+'" id="horce_name" value="'+data.Horse+'"/></div><div class="field"><label for="race_sire">Sire/Dam</label> <input type="text" name="race_sire_'+idx+'" id="race_sire" value="'+data.Sire+'"/></div><div class="field"><label for="race_Draw">Draw</label><input type="text" name="race_draw_'+idx+'" id="race_Draw" value="'+data.Draw+'"/></div><div class="field"><label for="race_colors">Colors</label><input type="text" name="race_colors_'+idx+'" id="race_colors" value="'+data.Colors+'"/></div><div class="field"><label for="race_owner">Owner</label><input type="text" name="race_owner_'+idx+'" id="race_owner" value="'+data.Owner+'"/></div><div class="field"><label for="race_trainer">Trainer</label><input type="text" name="race_trainer_'+idx+'" id="race_trainer" value="'+data.Trainer+'"/></div><div class="field"><label for="race_jockey">Jockey</label><input type="text" name="race_jockey_'+idx+'" id="race_jockey" value="'+data.Jockey+'"/></div><div class="field"><label for="race_weight">Weight</label><input type="text" name="race_weight_'+idx+'" id="race_weight" value="'+data.Weight+'"/></div><div class="field"><label for="race_earnings">Earnings</label><input type="text" name="race_earnings_'+idx+'" id="race_earnings" value="'+data.Earnings+'"/></div><div class="field"><label for="race_form">Form</label><input type="text" name="race_form_'+idx+'" id="race_form" value="'+data.Form+'"/></div><div class="field"><label for="race_rating">Rating</label><input type="text" name="race_rating_'+idx+'" id="race_rating" value="'+data.Rating+'"/></div><div class="field"><label for="race_blink">Blink</label><input type="text" name="race_blink_'+idx+'" id="race_blink" value="'+data.Blink+'"/></div><div class="field"><label for="race_coupled">Coupled</label><input type="text" name="race_coupled_'+idx+'" id="race_coupled" value="'+data.Coupled+'"/></div><div class="field"><label for="race_claim">Claim</label><input type="text" name="race_claim_'+idx+'" id="race_claim" value="'+data.Claim+'"/></div><div class="field"><label for="race_breeders">Breeders</label><input type="text" name="race_breeders_'+idx+'" id="race_breeders" value="'+data.Breeders+'"/></div></div>');
                      });
                      tableDom.push('<input type="hidden" name="totalRows" value="'+tableData.length+'" />');
                      raceStatusTable.empty();
                      raceStatusTable.append(tableDom);
                }
            });
        }
    });
});