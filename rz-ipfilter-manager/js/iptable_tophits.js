jQuery(document).ready(function ($) {
    $('#IPFilterTopAddressHitsTable').jtable({
        title: 'Top Offending IP addresses (unique)',
        actions: {
            listAction: function (postData, jtParams) {
                return $.Deferred(function ($dfd) {
                    $.ajax({
                        url: `${iptable_endpoint_url}`,
                        type: 'POST',
                        dataType: 'json',
                        data: { action: 'getHitRecords',
                                groupby: 'address',
                                maxCount: 25 },
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
            count: {
                title: 'Count',
                width: '10%'
            },
            remote_addr: {
                title: 'IP Address',
                width: '25%'
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
            }
        }
    });
    $('#IPFilterTopAddressHitsTable').jtable('load');
});



jQuery(document).ready(function ($) {
    $('#IPFilterTopCountriesHitsTable').jtable({
        title: 'Top Offending Countries (unique)',
        actions: {
            listAction: function (postData, jtParams) {
                return $.Deferred(function ($dfd) {
                    $.ajax({
                        url: `${iptable_endpoint_url}`,
                        type: 'POST',
                        dataType: 'json',
                        data: { action: 'getHitRecords',
                                groupby: 'country_code',
                                maxCount: 10 },
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
            count: {
                title: 'Count',
                width: '10%'
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
            }
        }
    });
    $('#IPFilterTopCountriesHitsTable').jtable('load');
});



jQuery(document).ready(function ($) {
    $('#IPFilterTopNetworksHitsTable').jtable({
        title: 'Top Offending Sub-networks (unique in CIDR notation)',
        actions: {
            listAction: function (postData, jtParams) {
                return $.Deferred(function ($dfd) {
                    $.ajax({
                        url: `${iptable_endpoint_url}`,
                        type: 'POST',
                        dataType: 'json',
                        data: { action: 'getHitRecords',
                                groupby: 'address_cidr',
                                maxCount: 10 },
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
            count: {
                title: 'Count',
                width: '10%'
            },
            address_cidr: {
                title: "Network (CIDR)",
                width: '20%',
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
            }
        }
    });
    $('#IPFilterTopNetworksHitsTable').jtable('load');
});