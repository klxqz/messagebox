(function($) {
    $.messagebox = {
        options: {},
        init: function(options) {
            this.options = options;
            this.initButtons();
            return this;
        },
        initButtons: function() {
            $('#add-but').click(function() {
                $.messagebox.messageDialog();
                return false;
            });
            $('#messagebox-list').on('click', '.edit-but', function() {
                var messagebox_id = $(this).closest('tr').data('messagebox-id');
                $.messagebox.messageDialog(messagebox_id);
                return false;
            });
            $('#messagebox-list').on('click', '.delete-but', function() {
                var messagebox_id = $(this).closest('tr').data('messagebox-id');
                $(this).after('<i class="icon16 loading"></i>');
                $.messagebox.deleteMessagebox(messagebox_id);
                return false;
            });
        },
        messageDialog: function(messagebox_id) {
            messagebox_id = messagebox_id || null;
            var showDialog = function() {
                $('#messagebox-dialog').waDialog({
                    disableButtonsOnSubmit: true,
                    onLoad: function() {
                        if ($('#messagebox-description-content').length) {
                            $.messagebox.initMessageboxWysiwyg($(this));
                        }
                    },
                    onSubmit: function(d) {
                        var form = $(this);
                        if ($('#messagebox-description-content').length) {
                            $('#messagebox-description-content').waEditor('sync');
                        }
                        $.ajax({
                            type: 'POST',
                            url: form.attr('action'),
                            dataType: 'json',
                            data: form.serialize(),
                            success: function(data, textStatus, jqXHR) {
                                if (data.status == 'ok') {
                                    if (messagebox_id && $('#messagebox-list').find('tr[data-messagebox-id=' + messagebox_id + ']').length) {
                                        $('#messagebox-list').find('tr[data-messagebox-id=' + messagebox_id + '] .messagebox-name').html(data.data.messagebox.name);
                                        $('#messagebox-list').find('tr[data-messagebox-id=' + messagebox_id + '] .messagebox-helper').html(data.data.messagebox.helper);
                                    } else {
                                        $('<tr data-messagebox-id="' + data.data.messagebox.id + '">\
                                            <td class="messagebox-name">' + data.data.messagebox.name + '</td>\\n\
                                            <td class="messagebox-helper">' + data.data.messagebox.helper + '</td>\
                                            <td><a class="edit-but" href="#"><i class="icon16 edit"></i></a></td>\
                                            <td><a class="delete-but" href="#"><i class="icon16 cross"></i></a></td>\
                                        </tr>').appendTo('#messagebox-list');
                                    }
                                    $('#dialog-response').text(data.data.message);
                                    $('#dialog-response').css('color','green');
                                    $('#messagebox-dialog .cancel').click();
                                }
                                if (data.status == 'fail') {
                                    $('#dialog-response').text(data.errors);
                                    $('#dialog-response').css('color','red');
                                }

                            }
                        });
                        return false;
                    }
                });
            };
            var d = $('#messagebox-dialog');
            var p;
            if (!d.length) {
                p = $('<div></div>').appendTo('body');
            } else {
                p = d.parent();
            }
            if (messagebox_id) {
                p.load('?plugin=messagebox&action=dialog&messagebox_id=' + messagebox_id, showDialog);
            } else {
                p.load('?plugin=messagebox&action=dialog', showDialog);
            }


        },
        deleteMessagebox: function(messagebox_id) {
            $.ajax({
                type: 'POST',
                url: '?plugin=messagebox&action=delete',
                dataType: 'json',
                data: {
                    messagebox_id: messagebox_id
                },
                success: function(data, textStatus, jqXHR) {
                    if (data.status == 'ok') {
                        $('#messagebox-list').find('tr[data-messagebox-id=' + messagebox_id + ']').remove();
                    }

                }
            });
        },
        initMessageboxWysiwyg: function(d) {
            var field = d.find('.field.description');
            field.find('i').hide();
            field.find('.s-editor-core-wrapper').show();
            var height = (d.find('.dialog-window').height() * 0.8) || 350;
            $('#messagebox-description-content').waEditor({
                lang: wa_lang,
                toolbarFixedBox: false,
                maxHeight: height,
                minHeight: height,
                uploadFields: d.data('uploadFields')
            });
        }
    };
})(jQuery); 