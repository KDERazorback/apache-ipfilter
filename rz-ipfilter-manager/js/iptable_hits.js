jQuery(document).ready(function ($) {
    $('#IPFilterHitsTable').jtable({
        title: 'Filtered IP History',
        paging: true,
        pageSize: 50,
        actions: {
            listAction: function (postData, jtParams) {
                console.log("listAction called.");
                return $.Deferred(function ($dfd) {
                    $.ajax({
                        url: `${iptable_endpoint_url}`,
                        type: 'POST',
                        dataType: 'json',
                        data: { action: 'getHitRecords',
                                startIndex: jtParams.jtStartIndex,
                                pageSize: jtParams.jtPageSize,
                                //groupby: 'country_code' },
                                sorting: jtParams.jtSorting },
                        success: function (data) {
                            $dfd.resolve(data);
                        },
                        error: function () {
                            $dfd.reject();
                        }
                    });
                });
            },

            deleteAction: function (postData) {
                console.log("deleteAction called.");
                return $.Deferred(function ($dfd) {
                    $.ajax({
                        url: `${iptable_endpoint_url}`,
                        type: 'POST',
                        dataType: 'json',
                        data: { action: 'deleteHitRecord', data: postData },
                        success: function (data) {
                            $dfd.resolve(data);
                        },
                        error: function () {
                            $dfd.reject();
                        }
                    });
                });
            },
        },
        fields: {
            id: {
                key: true,
                list: false
            },
            filter_id: {
                title: 'Filter ID',
                width: '5%'
            },
            remote_addr: {
                title: 'IP Address',
                width: '10%'
            },
            date: {
                title: 'Record date',
                width: '10%',
                type: 'date',
            },
            country_code: {
                title: "CC",
                width: '5%',
                display: function (data) {
                    return '<img style="margin-right: 4px" src=' + `${countryimg_endpoint_url}` + "?cc=" + data.record.country_code + ' />' + data.record.country_code;
                }
            },
            country_name: {
                title: "Country Name",
                width: '15%',
            },
            address_cidr: {
                title: "Sub Network Filter",
                width: '10%',
            },
            referrer: {
                title: 'Referrer',
                width: '20%'
            },
            useragent: {
                title: 'User Agent',
                width: '25%'
            }
        }
    });
    $('#IPFilterHitsTable').jtable('load');
});