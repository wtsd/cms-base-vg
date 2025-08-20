function startAdmin() {
    var prefix = 'adm';
    /* Category */
    $('.admName').on('dblclick', function (e) {
        var $h1 = $(this),
            name = $h1.text();
        $h1.hide().before('<input type="text" value="' + name.trim() + '" id="name">');
        $('.admSave').show();
    });

    $('.admLead').on('dblclick', function (e) {
        var $div = $(this),
            lead = $div.html();
        $div.hide().before('<textarea id="lead">' + lead.trim() + '</textarea>');
        $('.admSave').show();
    });

    $('.admFText').on('dblclick', function (e) {
        var $div = $(this),
            ftext = $div.html();
        $div.hide().before('<textarea id="ftext">' + ftext.trim() + '</textarea>');
        $('.admSave').show();
    });

    $('.admSave').on('click', function (e) {
        var $btn = $(this),
            id = $('#id').attr('data-id'),
            name = $('input#name').hide().val(),
            lead = $('textarea#lead').hide().val(),
            ftext = $('textarea#ftext').hide().val(),
            values = {
                'id': id,
                };

            if (name != undefined) {
                values.cname = name;
            }

            if (lead != undefined) {
                values.lead = lead;
            }

            if (ftext != undefined) {
                values.ftext = ftext;
            }


        $.ajax({
            url: "/" + prefix + "/ajax/",
            type: "post",
            data: {
                'act' : 'ajax',
                'model' : 'Content\\Category',
                'controller' : 'FESave',
                'values' : values
            },
            dataType: "json",
            beforeSend: function () {
                $btn.attr('disabled', 'disabled');
            },
            success: function (data) {
                if (name != undefined) {
                    $('.admName').html(name).show();
                }
                if (lead != undefined) {
                    $('.admLead').html(lead).show();
                }
                if (ftext != undefined) {
                    $('.admFText').html(ftext).show();
                }
                $btn.removeAttr('disabled').hide();
                console.log(data);
            }
        });

        

    });
}