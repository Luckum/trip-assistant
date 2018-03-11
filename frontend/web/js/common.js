function getTotal()
{
    $.ajax({
        url: "/service/get-formula",
        type: "POST",
        data: {s_id: $("#service-id-txt").val()},
        success: function(data) {
            var formula = JSON.parse(data.formula);
            parseFormula(formula);
        }
    });
}

function parseFormula(formula)
{
    var check = 1;
    var total = 0;
    
    $.each(formula, function() {
        if (typeof(this.logic) !== "undefined") {
            if (this.logic == "xor" || this.logic == "por") {
                if (check == 1) {
                    return false;
                } else {
                    check = 1;
                }
            }
        }
        if (typeof(this.field_id) !== 'undefined') {
            if ($.type(this.field_id) == 'object') {
                if ($("#" + this.field_id.field_id + " option:selected").attr("id") != this.field_id.field_id + "-0") {
                    
                } else {
                    check = 0;
                }
            } else {
                if ($("#" + this.field_id).val()) {
                    
                } else {
                    check = 0;
                }
            }
        }
        if (typeof(this.related) !== "undefined") {
            if ($("#" + this.related.field_1 + " option:selected").attr("id") != this.related.field_1 + "-0") {
                
            } else {
                check = 0;
            }
            if ($("#" + this.related.field_2 + " option:selected").attr("id") != this.related.field_2 + "-0") {
                
            } else {
                check = 0;
            }
        }
    });
    
    if (check == 1) {
        //console.log("checked");
        var res = parseFormulaDirect(formula, 0, 0, 0);
        //console.log("returned res - " + res);
        if (res == 0 && formula.length == 1) {
            if (formula[0].related != "undefined") {
                for (var t = 0; t < formula[0].related.related.length; t ++) {
                    if (formula[0].related.related[t][0] == $("#" + formula[0].related.field_2 + " option:selected").text()) {
                        var res = formula[0].related.related[t][$("#" + formula[0].related.field_1 + " option:selected").text()];
                    }
                }
            }
        }
        
        total = res;
        
    } else {
        //console.log("not checked");
    }
    $("#service-total").html("$" + total);
    $("#service-total-price").val(total);
}

function parseFormulaDirect(formula, res, por_step, por_idx)
{
    for (var i = 0; i < formula.length; i ++) {
        //console.log(formula[i]);
        if (typeof(formula[i].sign) !== "undefined") {
            if (res == 0) {
                if ($.type(formula[i - 1].field_id) == 'object') {
                    if (formula[i - 1].field_id.option_sign == 'plus') {
                        var options = formula[i - 1].field_id.options.split(';');
                        for (var j = 0; j < options.length; j ++) {
                            var opt = options[j].split(':');
                            if ($("#" + formula[i - 1].field_id.field_id + " option:selected").attr("id") == opt[0]) {
                                var res1 = opt[1];
                            }
                        }
                        if (typeof(res1) === "undefined") {
                            var res1 = 0;
                        }
                    }
                    if (formula[i - 1].field_id.option_sign == 'multiply') {
                        var res1 = $("#" + formula[i - 1].field_id.field_id).val() == '...' ? 0 : $("#" + formula[i - 1].field_id.field_id).val();
                    }
                } else {
                    if (typeof(formula[i - 1].field_id) !== "undefined") {
                        if (formula[i - 1].field_id.indexOf("date") !== -1) {
                            var date1 = new Date($("#" + formula[i - 1].field_id).val());
                            date1 = date1.getTime();
                        } else {
                            var res1 = $("#" + formula[i - 1].field_id).val();
                        }
                    } else {
                        if (typeof(formula[i - 1].related) !== "undefined") {
                            for (var t = 0; t < formula[i - 1].related.related.length; t ++) {
                                if (formula[i - 1].related.related[t][0] == $("#" + formula[i - 1].related.field_2 + " option:selected").text()) {
                                    var res1 = formula[i - 1].related.related[t][$("#" + formula[i - 1].related.field_1 + " option:selected").text()];
                                }
                            }
                        } else {
                            var res1 = formula[i - 1].value;
                        }
                    }
                }
                //console.log("res1 - " + res1);
            }
            
            if ($.type(formula[i + 1].field_id) == 'object') {
                if (formula[i + 1].field_id.option_sign == 'plus') {
                    var options = formula[i + 1].field_id.options.split(';');
                    for (var j = 0; j < options.length; j ++) {
                        var opt = options[j].split(':');
                        if ($("#" + formula[i + 1].field_id.field_id + " option:selected").attr("id") == opt[0]) {
                            var res2 = opt[1];
                        }
                    }
                }
                if (formula[i + 1].field_id.option_sign == 'multiply') {
                    var res2 = $("#" + formula[i + 1].field_id.field_id).val() == '...' ? 0 : $("#" + formula[i + 1].field_id.field_id).val();
                }
            } else {
                if (typeof(formula[i + 1].field_id) !== "undefined") {
                    if (formula[i + 1].field_id.indexOf("date") !== -1) {
                        var date2 = new Date($("#" + formula[i + 1].field_id).val());
                        date2 = date2.getTime();
                    } else {
                        var res2 = $("#" + formula[i + 1].field_id).val();
                    }
                } else {
                    if (typeof(formula[i + 1].related) !== "undefined") {
                        for (var t = 0; t < formula[i + 1].related.related.length; t ++) {
                            if (formula[i + 1].related.related[t][0] == $("#" + formula[i + 1].related.field_2 + " option:selected").text()) {
                                var res2 = formula[i + 1].related.related[t][$("#" + formula[i + 1].related.field_1 + " option:selected").text()];
                            }
                        }
                    } else {
                        var res2 = formula[i + 1].value;
                    }
                }
            }
            //console.log("res2 - " + res2);
            if ((typeof(date1) !== 'undefined' && typeof(date2) !== 'undefined') && (date1 !== 'undefined' && date2 !== 'undefined')) {
                //console.log('date');
                switch (formula[i].sign) {
                    case 'plus':
                        var timeDiff = Math.abs(date2 + date1);
                        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
                    break;
                    case 'minus':
                        var timeDiff = Math.abs(date2 - date1);
                        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
                    break;
                }
                res = diffDays;
                date1 = 'undefined';
                date2 = 'undefined';
            } else {
                //console.log('sign - ' + formula[i].sign);
                if (res == 0) {
                    res = res1;
                }
                switch (formula[i].sign) {
                    case 'plus':
                        res += parseInt(res2);
                    break;
                    case 'minus':
                        res -= res2;
                    break;
                    case 'multiply':
                        res *= res2;
                    break;
                    case 'divide':
                        res /= res2;
                    break;
                }
            }
            //console.log("res - " + res);
        } else if (typeof(formula[i].logic) !== "undefined") {
            if (formula[i].logic == "xor") {
                if (res != 0) {
                    break;
                }
            }
            if (formula[i].logic == "por") {
                if (res != 0) {
                    //console.log("to recursion");
                    //console.log("i - " + i);
                    var res_1 = parseFormulaDirect(formula.slice(++i), 0, 1, por_idx);
                    //console.log("returned res_1 - " + res_1);
                    res += parseInt(res_1);
                    break;
                }
            }
        }
    }
    return res;
}

