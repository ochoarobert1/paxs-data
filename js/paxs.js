var dataSet = [];

function initPaxsDdtatables() {
    jQuery('#paxsTables').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.22/i18n/Spanish.json"
        },
        responsive: true,
        processing: true,
        paging: true,
        data: dataSet,
        columns: [{
                title: 'Apellidos'
            },
            {
                title: 'Nombres'
            },
            {
                title: 'Cédula'
            },
            {
                title: 'Pasaporte'
            },
            {
                title: 'Fecha de Nacimiento'
            },
            {
                title: 'Fecha de Vencimiento'
            },
            {
                title: 'Acciones'
            }
        ],
        columnDefs: [{
                "render": function(data, type, row) {
                    if (data == '') {
                        return '<strong>-----</strong>';
                    } else {
                        return data;
                    }
                },
                "targets": 2
            },
            {
                "render": function(data, type, row) {
                    if (data == '') {
                        return '<strong>-----</strong>';
                    } else {
                        return data;
                    }
                },
                "targets": 3
            },
            {
                "render": function(data, type, row) {
                    if (data != undefined) {
                        var myDate = data.split('-');
                        return myDate[2] + '-' + myDate[1] + '-' + myDate[0];

                    } else {
                        return 'Sin Fecha';
                    }
                },
                "targets": 4
            },
            {
                "render": function(data, type, row) {
                    if (data != undefined) {
                        var myDate = data.split('-');
                        return myDate[2] + '-' + myDate[1] + '-' + myDate[0];

                    } else {
                        return '<strong>Sin Fecha</strong>';
                    }
                },
                "targets": 5
            },
            {
                "render": function(data, type, row) {
                    return '<a onclick="viewHistorical(' + row[6] + ')" title="Ver Histórico"><span class="dashicons dashicons-search"></span></a>   <a onclick="editDetails(' + row[6] + ')" title="Editar Registro"><span class="dashicons dashicons-edit-page"></span></a>  <a onclick="eraseRow(' + row[6] + ')" title="Eliminar Registro"><span class="dashicons dashicons-trash"></span></a>';
                },
                "targets": 6
            }
        ]
    });
}

function refreshPaxsDatatables() {
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            action: 'main_data_parser'
        },
        success: function(response) {
            dataSet = jQuery.parseJSON(response);
            jQuery('#paxsTables').DataTable().destroy();
            initPaxsDdtatables();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            reject(error);
        }
    });
}

jQuery(document).ready(function(e) {
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            action: 'main_data_parser'
        },
        success: function(response) {
            dataSet = jQuery.parseJSON(response);
            initPaxsDdtatables();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            reject(error);
        }
    });

    jQuery(document).on('click', '#newRegistrySubmit', function(e) {
        e.preventDefault();
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'add_new_data_parser',
                data: jQuery('#newRegistryForm').serialize()
            },
            beforeSend: function() {
                jQuery('.modal-loader').removeClass('modal-hidden');
            },
            success: function(response) {
                jQuery('.modal-loader').addClass('modal-hidden');
                jQuery('.modal-content').addClass('modal-content-hidden');
                jQuery('.modal-container').addClass('modal-hidden');
                refreshPaxsDatatables();
            },
            error: function(error) {
                console.log(error);
            }
        });
    });

    jQuery(document).on('click', '#editRegistrySubmit', function(e) {
        e.preventDefault();
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'add_edit_data_parser',
                data: jQuery('#editRegistryForm').serialize()
            },
            beforeSend: function() {
                jQuery('.modal-loader').removeClass('modal-hidden');
            },
            success: function(response) {
                jQuery('.modal-loader').addClass('modal-hidden');
                jQuery('.modal-content').addClass('modal-content-hidden');
                jQuery('.modal-container').addClass('modal-hidden');
                refreshPaxsDatatables();
            },
            error: function(error) {
                console.log(error);
            }
        });
    });

    jQuery(document).on('click', '.close-modal', function(e) {
        e.preventDefault();
        jQuery('.modal-loader').addClass('modal-hidden');
        jQuery('.modal-content').addClass('modal-content-hidden');
        jQuery('.modal-container').addClass('modal-hidden');
    });

    jQuery(document).on('click', '#paxs_media_manager', function(e) {

        e.preventDefault();
        var image_frame;
        if (image_frame) {
            image_frame.open();
        }
        // Define image_frame as wp.media object
        image_frame = wp.media({
            title: 'Select Media',
            multiple: false,
            library: {
                type: 'image',
            }
        });

        image_frame.on('close', function() {
            // On close, get selections and save to the hidden input
            // plus other AJAX stuff to refresh the image preview
            var selection = image_frame.state().get('selection');
            var gallery_ids = new Array();
            var my_index = 0;
            selection.each(function(attachment) {
                gallery_ids[my_index] = attachment['id'];
                my_index++;
            });
            var ids = gallery_ids.join(",");
            jQuery('input#image_url').val(ids);
            Refresh_Image(ids);
        });

        image_frame.on('open', function() {
            // On open, get the id from the hidden input
            // and select the appropiate images in the media manager
            var selection = image_frame.state().get('selection');
            var ids = jQuery('input#image_url').val().split(',');
            ids.forEach(function(id) {
                var attachment = wp.media.attachment(id);
                attachment.fetch();
                selection.add(attachment ? [attachment] : []);
            });

        });

        image_frame.open();
    });
});

function Refresh_Image(the_id) {
    var data = {
        action: 'paxs_get_image',
        id: the_id
    };

    jQuery.get(ajaxurl, data, function(response) {

        if (response.success === true) {
            jQuery('#paxs-preview-image').replaceWith(response.data.image);
        }
    });
}


jQuery('#newRegistry').on('click', function(e) {
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            action: 'new_data_parser'
        },
        beforeSend: function() {
            jQuery('.modal-container').removeClass('modal-hidden');
        },
        success: function(response) {
            jQuery('.modal-content').html(response);
            jQuery('.modal-content').removeClass('modal-content-hidden');
        },
        error: function(error) {
            console.log(error);
        }
    });
});

function viewHistorical(id) {
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            action: 'historical_data_parser',
            id: id
        },
        beforeSend: function() {
            jQuery('.modal-container').removeClass('modal-hidden');
        },
        success: function(response) {
            jQuery('.modal-content').html(response);
            jQuery('.modal-content').removeClass('modal-content-hidden');
        },
        error: function(error) {
            console.log(error);
        }
    });
}

function editDetails(id) {
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            action: 'edit_data_parser',
            id: id
        },
        beforeSend: function() {
            jQuery('.modal-container').removeClass('modal-hidden');
        },
        success: function(response) {
            jQuery('.modal-content').html(response);
            jQuery('.modal-content').removeClass('modal-content-hidden');
        },
        error: function(error) {
            console.log(error);
        }
    });
}

function eraseRow(id) {
    swal({
            title: "¿Esta seguro de eliminar este registro?",
            text: "No podra deshacer este paso",
            type: "warning",
            showCancelButton: true,
            reverseButtons: false,
            cancelButtonText: "Cancelar",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Eliminar",
            closeOnConfirm: false
        },

        function() {
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'erase_data_parser',
                    id: id
                },
                success: function(response) {
                    swal("¡Hecho!",
                        "El Registro ha sido eliminado exitosamente.",
                        "success");
                    refreshPaxsDatatables();
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
}

function newHistorical() {
    jQuery('.new-row').before('<tr class="add-row"><td></td><td><input type="text" name="ruta_vuelo" class="form-control"></td><td><input type="date" class="date-dynamic" name="fecha_vuelo" class="form-control"></td><td><input type="text" name="aerolinea" class="form-control"></td><td><input type="text" name="nro_vuelo" class="form-control"></td><td><input type="text" name="nro_boleto" class="form-control"></td><td><input type="text" name="reservacion" class="form-control"></td><td><button class="add-btn"><span class="dashicons dashicons-yes"></span></button> <button class="cancel-btn"><span class="dashicons dashicons-no"></span></button></td></tr>');

    //jQuery('.date-dynamic').datepicker();
}

function eraseHistorical(id) {
    swal({
            title: "¿Esta seguro de eliminar este registro?",
            text: "No podra deshacer este paso",
            type: "warning",
            showCancelButton: true,
            reverseButtons: false,
            cancelButtonText: "Cancelar",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Eliminar",
            closeOnConfirm: false
        },

        function() {
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'erase_historical_data_parser',
                    id: id
                },
                success: function(response) {
                    jQuery('.historical-body').html('');
                    jQuery('.historical-body').html(response);
                    swal("¡Hecho!",
                        "El Registro ha sido eliminado exitosamente.",
                        "success");
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
}

jQuery(document).on('click', '.cancel-btn', function(e) {
    e.preventDefault();
    jQuery('.add-row').remove();
});

jQuery(document).on('click', '.add-btn', function(e) {
    e.preventDefault();
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            action: 'new_historical_data_parser',
            data: jQuery('#historicalForm').serialize()
        },
        success: function(response) {
            jQuery('.historical-body').html('');
            jQuery('.historical-body').html(response);
            jQuery('.add-row').remove();
        },
        error: function(error) {
            console.log(error);
        }
    });
});

function editHistorical(id) {
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            action: 'edit_historical_data_parser',
            id: id
        },
        success: function(response) {
            jQuery('.new-row').before(response);
        },
        error: function(error) {
            console.log(error);
        }
    });
}

jQuery(document).on('click', '.edit-btn', function(e) {
    e.preventDefault();
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,

        data: {
            action: 'edit_new_historical_data_parser',
            data: jQuery('#historicalForm').serialize()
        },
        success: function(response) {
            jQuery('.historical-body').html('');
            jQuery('.historical-body').html(response);
            jQuery('.add-row').remove();
        },
        error: function(error) {
            console.log(error);
        }
    });
});

jQuery(document).on('click', '#reportsDateBtn', function(e) {
    e.preventDefault();
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            action: 'reports_date',
            data: jQuery('#reportsByDate').serialize()
        },
        /*
        xhrFields: {
            responseType: 'blob'
        },
        */
        beforeSend: function() {
            jQuery('#reportsDateBtn').next().removeClass('modal-hidden');
        },
        success: function(response) {
            jQuery('#reportsDateBtn').next().addClass('modal-hidden');
            jQuery('.paxs-report-generator-container').html(response);
            /*
            
            var filename = "";
            var disposition = xhr.getResponseHeader('Content-Disposition');
            if (disposition && disposition.indexOf('attachment') !== -1) {
                var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                var matches = filenameRegex.exec(disposition);
                if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
            }

            if (typeof window.navigator.msSaveBlob !== 'undefined') {
                // IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                window.navigator.msSaveBlob(blob, filename);
            } else {
                var URL = window.URL || window.webkitURL;
                var downloadUrl = URL.createObjectURL(blob);

                if (filename) {
                    // use HTML5 a[download] attribute to specify filename
                    var a = document.createElement("a");
                    // safari doesn't support this yet
                    if (typeof a.download === 'undefined') {
                        window.location.href = downloadUrl;
                    } else {
                        a.href = downloadUrl;
                        a.download = filename;
                        document.body.appendChild(a);
                        a.click();
                    }
                } else {
                    window.location.href = downloadUrl;
                }

                setTimeout(function() {
                    URL.revokeObjectURL(downloadUrl);
                }, 100); // cleanup
            }
            */
        },
        error: function(error) {
            console.log(error);
        }
    });
});

jQuery(document).on('click', '#reportsUserBtn', function(e) {
    e.preventDefault();
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            action: 'reports_user',
            data: jQuery('#reportsByUser').serialize()
        },
        beforeSend: function() {
            jQuery('#reportsUserBtn').next().removeClass('modal-hidden');
        },
        success: function(response) {
            jQuery('#reportsUserBtn').next().addClass('modal-hidden');

        },
        error: function(error) {
            console.log(error);
        }
    });
});

jQuery(document).on('click', '#reportsFlightBtn', function(e) {
    e.preventDefault();
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            action: 'reports_flight',
            data: jQuery('#reportsByFlight').serialize()
        },
        beforeSend: function() {
            jQuery('#reportsFlightBtn').next().removeClass('modal-hidden');
        },
        success: function(response) {
            jQuery('#reportsFlightBtn').next().addClass('modal-hidden');

        },
        error: function(error) {
            console.log(error);
        }
    });
});