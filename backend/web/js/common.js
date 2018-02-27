$(document).ready(function() {
    $(".formula").click(function() {
        $("<button>", {
            text: $(this).text(),
            class: "btn btn-default",
            'data-field-id': $(this).attr('data-field-id'),
            'data-field-sign': $(this).attr('data-field-sign'),
            on: {
                click: function(event) {
                    if (confirm("Вы уверены, что хотите удалить этот элемент?")) {
                        $(this).remove();
                    }
                }
            }
        }).appendTo("#formula-result");
    });
    
    $(".formula-option").click(function() {
        $("#formula-options-container").show();
        $("#options-value").html('');
        var option_parent = $(this).parent().parent();
        var field_id = option_parent.attr('data-field');
        var data_field_id = option_parent.attr('data-field-id');
        var options = "";
        $(".option-multiply").attr("data-field", field_id);
        $(".option-multiply").attr("data-field-id", data_field_id);
        option_parent.find("li").each(function() {
            $("<label>", {
                text: $(this).find("a").text() + ":",
                css: {
                    margin: "0 10px 0 0"
                }
            }).appendTo("#options-value");
            $("<input>", {
                type: 'text',
                width: 40,
                class: 'option-txt',
                'data-option-id-txt': $(this).find("a").attr("data-option-id"),
                css: {
                    margin: "0 15px 0 0"
                }
            }).appendTo("#options-value");
        });
        $("<button>", {
            text: "Вставить",
            class: "btn btn-xs btn-success",
            on: {
                click: function(event) {
                    $("#formula-options-container").hide();
                    $("#options-value").hide();
                    $("<button>", {
                        text: $("[data-field-btn=" + field_id + "]").text(),
                        class: "btn btn-default",
                        'data-field-id': data_field_id,
                        'data-option-sign': 'plus',
                        on: {
                            click: function(event) {
                                if (confirm("Вы уверены, что хотите удалить этот элемент?")) {
                                    $(this).remove();
                                }
                            }
                        }
                    }).appendTo("#formula-result");
                    $('.option-txt').each(function() {
                        options += $(this).attr("data-option-id-txt") + ":" + $("[data-option-id-txt=" + $(this).attr("data-option-id-txt") + "]").val() + ";";
                    });
                    $("[data-field-id=" + data_field_id + "]").attr("data-option-options", options);
                }
            }
        }).appendTo("#options-value");
    });
    
    $(".option-multiply").click(function() {
        $("#formula-options-container").hide();
        var field_id = $(this).attr('data-field');
        var data_field_id = $(this).attr('data-field-id');
        $("<button>", {
            text: $("[data-field-btn=" + field_id + "]").text(),
            class: "btn btn-default",
            'data-field-id': data_field_id,
            'data-option-sign': 'multiply',
            on: {
                click: function(event) {
                    if (confirm("Вы уверены, что хотите удалить этот элемент?")) {
                        $(this).remove();
                    }
                }
            }
        }).appendTo("#formula-result");
    });
    
    $(".option-set-value").click(function() {
        $("#options-value").show();
    });
    
    $(".formula-value").click(function() {
        $("#formula-value-container").show();
    });
    
    $(".formula-table").click(function() {
        $("#formula-table-check-container").show();
    });
    
    $("#formula-value-cancel-btn").click(function() {
        $("#formula-value-container").hide();
    });
    
    $("#formula-table-cancel-btn").click(function() {
        $("#formula-table-check-container").hide();
    });
    
    $("#formula-options-cancel-btn").click(function() {
        $("#formula-options-container").hide();
    });
    
    $("#formula-table-container-cancel-btn").click(function() {
        $("#formula-table-container").hide();
    });
    
    $("#formula-value-insert-btn").click(function() {
        $("#formula-value-container").hide();
        $("<button>", {
            text: $("#formula-value-txt").val(),
            class: "btn btn-default",
            'data-field-value': $("#formula-value-txt").val(),
            on: {
                click: function(event) {
                    if (confirm("Вы уверены, что хотите удалить этот элемент?")) {
                        $(this).remove();
                    }
                }
            }
        }).appendTo("#formula-result");
    });
    
    $("#formula-table-insert-btn").click(function() {
        var checked_cnt = 0;
        var checked_data = "";
        $(".table-checker").each(function() {
            //console.log(this);
            if (this.checked) {
                checked_cnt ++;
                checked_data += $(this).attr("data-options-checker") + ";";
                if (checked_cnt == 2) {
                    return false;
                }
            }
        });
        showTable(checked_data);
    });
    
    
    
    $("#formula-save").click(function() {
        var formula = {};
        $("#formula-result").find("button").each(function(e) {
            if ($(this).attr("data-field-id") != "sign" && $(this).attr("data-field-id") != "logic") {
                if (typeof($(this).attr("data-option-sign")) !== 'undefined') {
                    formula[e] = {'field_id': {
                        'field_id': $(this).attr("data-field-id"),
                        'options': $(this).attr("data-option-options"),
                        'option_sign': $(this).attr("data-option-sign")
                    }};
                } else {
                    if (typeof($(this).attr("data-field-id")) !== 'undefined') {
                        formula[e] = {'field_id': $(this).attr("data-field-id")};
                    } else {
                        if (typeof($(this).attr("data-field-value")) !== 'undefined') {
                            formula[e] = {'value': $(this).attr("data-field-value")};
                        } else {
                            var a = JSON.parse($(this).attr("data-field-related-ids"));
                            formula[e] = {'related': {
                                'field_1': a.field_1,
                                'field_2': a.field_2,
                                'related': JSON.parse($(this).attr("data-field-related"))
                            }};
                        }
                    }
                }
            } else if ($(this).attr("data-field-id") == "logic") {
                formula[e] = {'logic': $(this).attr("data-field-sign")};
            } else {
                formula[e] = {'sign': $(this).attr("data-field-sign")};
            }
        });
        
        //console.log(formula);
        $.ajax({
            url: "/admin/service/set-formula",
            type: "POST",
            data: {formula: formula, s_id: $("#service-id-txt").val()},
            success: function(response) {
                $("#message-formula").slideDown();
                setTimeout(function() { $("#message-formula").slideUp(); }, 3000);
            }
        });
    });
    
    $("#formula-update").click(function() {
        if ($("#formula-update").text() == "Сохранить") {
            $("#formula-update-options").find("input").each(function() {
                var this_input = this;
                if (typeof($(this_input).attr("data-option-id-txt")) !== "undefined") {
                    $("#formula-result").find("button").each(function(e) {
                        var this_button = this;
                        if (typeof($(this_button).attr("data-option-options")) !== "undefined") {
                            var options = $(this_button).attr("data-option-options").split(';');
                            for (var j = 0; j < options.length - 1; j ++) {
                                var opt = options[j].split(':');
                                if (opt[0] == $(this_input).attr("data-option-id-txt")) {
                                    $(this_button).attr("data-option-options", $(this_button).attr("data-option-options") + $(this_input).attr("data-option-id-txt") + ":" + $("[data-option-id-txt=" + $(this_input).attr("data-option-id-txt") + "]").val() + ";");
                                }
                            }
                        }
                    });
                }
                if (typeof($(this_input).attr("data-position")) !== "undefined") {
                    $("#formula-result").find("button").each(function(e) {
                        var this_button = this;
                        if ($(this_button).attr("id") == "btn-" + $(this_input).attr("data-position")) {
                            $(this_button).attr("data-field-value", $(this_input).val());
                            $(this_button).text($(this_input).val());
                        }
                    });
                }
                
            });
            $("#formula-result").find("button").each(function(e) {
                var this_button = this;
                if (typeof($(this_button).attr("data-option-options")) !== "undefined") {
                    var options = $(this_button).attr("data-option-options").split(';');
                    var half_options = "";
                    var half = (options.length - 1) / 2;
                    for (half; half < options.length - 1; half ++) {
                        half_options += options[half] + ";";
                    }
                    $(this_button).attr("data-option-options", half_options);
                }
            });
            $("#formula-update-options").hide();
            $("#formula-update-cancel").hide();
            $("#formula-update").text("Редактировать значения");
            $("#formula-save").show();
        } else {
            $("#formula-update-options").html("");
            $("#formula-result").find("button").each(function(e) {
                if (typeof($(this).attr("data-option-options")) !== "undefined") {
                    var options = $(this).attr("data-option-options").split(';');
                    for (var j = 0; j < options.length - 1; j ++) {
                        var opt = options[j].split(':');
                        $.ajax({
                            url: "/admin/service/get-option-label",
                            type: "POST",
                            async: false,
                            data: {o_id: opt[0]},
                            success: function(data) {
                                if (data.label) {
                                    $("<label>", {
                                        text: data.label,
                                        css: {
                                            margin: "0 10px 0 0"
                                        }
                                    }).appendTo("#formula-update-options");
                                    $("<input>", {
                                        type: 'text',
                                        width: 40,
                                        class: 'option-txt',
                                        'data-option-id-txt': opt[0],
                                        value: opt[1],
                                        css: {
                                            margin: "0 15px 0 0"
                                        }
                                    }).appendTo("#formula-update-options");
                                }
                            }
                        });
                    }
                }
                if (typeof($(this).attr("data-field-value")) !== "undefined") {
                    $("<label>", {
                        text: "Значение",
                        css: {
                            margin: "0 10px 0 0"
                        }
                    }).appendTo("#formula-update-options");
                    $("<input>", {
                        type: 'text',
                        width: 40,
                        'data-position': e,
                        value: $(this).attr("data-field-value"),
                        css: {
                            margin: "0 15px 0 0"
                        }
                    }).appendTo("#formula-update-options");
                }
                if (typeof($(this).attr("data-field-related")) !== "undefined") {
                    var this_button = this;
                    $("<table>", {
                        id: "formula-table-" + e,
                        class: "formula-table-tbl"
                    }).appendTo("#formula-update-options");
                    var c_fields = {"": {}};
                    var c_data = [];
                    var fields = JSON.parse($(this).attr("data-field-related"));
                    
                    $.each(fields[0], function(key, val) {
                        if (key != "0") {
                            c_fields[key] = {"class": "edit", "type": "int"};
                        }
                    });
                    for (var k = 0; k < fields.length; k ++) {
                        c_data[k] = fields[k];
                    }
                    
                    var formula_tbl = new Table({
                        id: 'formula-table-' + e,
                        fields: c_fields,
                        data: c_data
                    });
                    formula_tbl.render();
                    $("#formula-update").click(function() {
                        $(this_button).attr("data-field-related", JSON.stringify(formula_tbl.serialize()));
                    });
                    
                }
            });
            if ($("#formula-update-options").html() !== "") {
                $("#formula-update-options").show();
                $("#formula-update-cancel").show();
                $("#formula-update").text("Сохранить");
                $("#formula-save").hide();
            }
        }
        
    });
    
    $("#formula-update-cancel").click(function() {
        $("#formula-update-options").hide();
        $("#formula-update-cancel").hide();
        $("#formula-update").text("Редактировать значения");
    });
});

function showTable(c_data)
{
    $("#formula-table-check-container").hide();
    $("#formula-table-container").show();
    var opts = c_data.split(";");
    var c_fields = {"": {}};
    var c_data = [];
    var option_parent_1 = $("[data-options-ul=" + opts[0] + "]");
    var option_parent_2 = $("[data-options-ul=" + opts[1] + "]");
    option_parent_1.find("li").each(function() {
        c_fields[$(this).find("a").text()] = {"class": "edit", "type": "int"}
    });
    option_parent_2.find("li").each(function(e) {
        c_data[e] = [$(this).find("a").text()];
    });
    var formula_tbl = new Table({
        id: 'formula-table',
        fields: c_fields,
        data: c_data
    });
    formula_tbl.render();
    $("#formula-table-container-insert-btn").click(function() {
        $("#formula-table-container").hide();
        //console.log(formula_tbl.serialize());
        $("<button>", {
            text: "Связанное значение",
            class: "btn btn-default",
            'data-field-related-ids': JSON.stringify({'field_1': option_parent_1.attr("data-field-id"), 'field_2': option_parent_2.attr("data-field-id")}),
            'data-field-related': JSON.stringify(formula_tbl.serialize()),
            on: {
                click: function(event) {
                    if (confirm("Вы уверены, что хотите удалить этот элемент?")) {
                        $(this).remove();
                    }
                }
            }
        }).appendTo("#formula-result");
    });
}

function priorityUp(s_id)
{
    $.ajax({
        url: "/admin/service/up",
        type: "POST",
        data: {id: s_id},
        success: function(response) {
            $.pjax.reload({container:"#service-index-pjax"});
        }
    });
}

function priorityDown(s_id)
{
    $.ajax({
        url: "/admin/service/down",
        type: "POST",
        data: {id: s_id},
        success: function(response) {
            $.pjax.reload({container:"#service-index-pjax"});
        }
    });
}

function getFormula()
{
    $.ajax({
        url: "/admin/service/get-formula",
        type: "POST",
        data: {s_id: $("#service-id-txt").val()},
        success: function(data) {
            if (data.formula) {
                $("#formula-save").hide();
                $("#formula-update").show();
                var fields = JSON.parse(data.formula);
                $.each(fields, function(e) {
                    var this_field = this;
                    if (typeof(this.field_id) !== "undefined") {
                        if ($.type(this.field_id) == "object") {
                            var f_id = this.field_id.field_id;
                            $.ajax({
                                url: "/admin/service/get-field-label",
                                type: "POST",
                                async: false,
                                data: {f_id: f_id},
                                success: function(data) {
                                    if (data.label) {
                                        var f_label = data.label;
                                        if (this_field.field_id.option_sign == "plus") {
                                            $("<button>", {
                                                text: f_label,
                                                class: "btn btn-default",
                                                'data-field-id': this_field.field_id.field_id,
                                                'data-option-sign': 'plus',
                                                'data-option-options': this_field.field_id.options,
                                                'id': "btn-" + e,
                                                on: {
                                                    click: function(event) {
                                                        if (confirm("Вы уверены, что хотите удалить этот элемент?")) {
                                                            $(this).remove();
                                                        }
                                                    }
                                                }
                                            }).appendTo("#formula-result");
                                        } else {
                                            $("<button>", {
                                                text: f_label,
                                                class: "btn btn-default",
                                                'data-field-id': this_field.field_id.field_id,
                                                'data-option-sign': 'multiply',
                                                'id': "btn-" + e,
                                                on: {
                                                    click: function(event) {
                                                        if (confirm("Вы уверены, что хотите удалить этот элемент?")) {
                                                            $(this).remove();
                                                        }
                                                    }
                                                }
                                            }).appendTo("#formula-result");
                                        }
                                    }
                                }
                            });
                        } else {
                            var f_id = this.field_id;
                            $.ajax({
                                url: "/admin/service/get-field-label",
                                type: "POST",
                                async: false,
                                data: {f_id: f_id},
                                success: function(data) {
                                    if (data.label) {
                                        var f_label = data.label;
                                        $("<button>", {
                                            text: f_label,
                                            class: "btn btn-default",
                                            'data-field-id': f_id,
                                            'id': "btn-" + e,
                                            on: {
                                                click: function(event) {
                                                    if (confirm("Вы уверены, что хотите удалить этот элемент?")) {
                                                        $(this).remove();
                                                    }
                                                }
                                            }
                                        }).appendTo("#formula-result");
                                    }
                                }
                            });
                        }
                    } else if (typeof(this.sign) !== "undefined") {
                        switch (this.sign) {
                            case "plus":
                                var sign_label = "+";
                            break;
                            case "minus":
                                var sign_label = "-";
                            break;
                            case "multiply":
                                var sign_label = "*";
                            break;
                            case "divide":
                                var sign_label = "/";
                            break;
                        }
                        $("<button>", {
                            text: sign_label,
                            class: "btn btn-default",
                            'data-field-id': 'sign',
                            'data-field-sign': this.sign,
                            'id': "btn-" + e,
                            on: {
                                click: function(event) {
                                    if (confirm("Вы уверены, что хотите удалить этот элемент?")) {
                                        $(this).remove();
                                    }
                                }
                            }
                        }).appendTo("#formula-result");
                    } else if (typeof(this.value) !== "undefined") {
                        $("<button>", {
                            text: this.value,
                            class: "btn btn-default",
                            'data-field-value': this.value,
                            'id': "btn-" + e,
                            on: {
                                click: function(event) {
                                    if (confirm("Вы уверены, что хотите удалить этот элемент?")) {
                                        $(this).remove();
                                    }
                                }
                            }
                        }).appendTo("#formula-result");
                    } else if (typeof(this.logic) !== "undefined") {
                        switch (this.logic) {
                            case "xor":
                                var logic_label = "Исключающее ИЛИ";
                            break;
                            case "por":
                                var logic_label = "ИЛИ +";
                            break;
                        }
                        $("<button>", {
                            text: logic_label,
                            class: "btn btn-default",
                            'data-field-id': 'logic',
                            'data-field-sign': this.logic,
                            'id': "btn-" + e,
                            on: {
                                click: function(event) {
                                    if (confirm("Вы уверены, что хотите удалить этот элемент?")) {
                                        $(this).remove();
                                    }
                                }
                            }
                        }).appendTo("#formula-result");
                    } else if (typeof(this.related) !== "undefined") {
                        $("<button>", {
                            text: "Связанное значение",
                            class: "btn btn-default",
                            'data-field-related-ids': JSON.stringify({'field_1': this.related.field_1, 'field_2': this.related.field_2}),
                            'data-field-related': JSON.stringify(this.related.related),
                            on: {
                                click: function(event) {
                                    if (confirm("Вы уверены, что хотите удалить этот элемент?")) {
                                        $(this).remove();
                                    }
                                }
                            }
                        }).appendTo("#formula-result");
                    }
                });
            }
        }
    });
}