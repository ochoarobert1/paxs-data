var dataSet = [];
jQuery(document).ready(function(e) {
    console.log('admin functions loaded');

    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            action: 'main_data_parser'
        },
        success: function(response) {
            dataSet = jQuery.parseJSON(response);
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
                                return '-----';
                            } else {
                                return data;
                            }
                        },
                        "targets": 2
                    },
                    {
                        "render": function(data, type, row) {
                            if (data == '') {
                                return '-----';
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
                                return 'Sin Fecha';
                            }
                        },
                        "targets": 5
                    },
                    {
                        "render": function(data, type, row) {
                            return '<a onclick="viewHistorical(' + row[6] + ')"><span class="dashicons dashicons-search"></span></a>   <a onclick="editDetails(' + row[6] + ')" title="Editar Detalles"><span class="dashicons dashicons-edit-page"></span></a>  <a onclick="eraseRow(' + row[6] + ')" title="Editar Detalles"><span class="dashicons dashicons-trash"></span></a>';
                        },
                        "targets": 6
                    }
                ]
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            reject(error);
        }
    });
});

function viewHistorical(id) {
    console.log(id);
}

function editDetails(id) {
    console.log(id);
}