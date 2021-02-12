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
});

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
