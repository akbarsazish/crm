

$(document).ready(
    function () {
        $(window).load(function () {
            $(".c-gallery__items img").click(function () {
                var src = $(this).attr("src");
                $(".c-gallery__img img").attr("src", src);
            });
            $("#modalBody").scrollTop($("#modalBody").prop("scrollHeight"));
        });
    } // document-ready
);
document
    .querySelector(".fa-bars")
    .parentElement.addEventListener("click", () => {
        // backdrop.classList.add('show');
    });

var baseUrl = "http://192.168.10.26:8080";
var myVar;
function setAdminStuffForAdmin(element,adminTypeId,driverId) {
    $(element).find("input:radio").prop("checked", true);
    let input = $(element).find("input:radio");
    let adminType = input.val().split("_")[1];
    let adminId = input.val().split("_")[0];
    $("#PoshtibanId").val(adminId);
    $("#subBazaryabId").val(adminId);
    $("#subListDashboardBtn").prop("disabled",false);
    $("#adminSn").val(adminId);
    if(adminTypeId==1){

        $("#adminInfo").css({"display":"inline"});
        $("#poshtibanInfo").css({"display":"none"});
        $("#bazaryabInfo").css({"display":"none"});
        $("#subListDashboardBtnPoshtiban").prop("disabled",false);
    }
    
    if(adminTypeId==2){
        $("#poshtibanInfo").css({"display":"inline"});
        $("#bazaryabInfo").css({"display":"none"});
        $("#subListDashboardBtnPoshtiban").prop("disabled",false);
    }

    if(adminTypeId==3){
        $("#bazaryabInfo").css({"display":"inline"});
        $("#poshtibanInfo").css({"display":"none"});
        $("#subListDashboardBtn").prop("disabled",false);
    }

    if(adminTypeId==4){
        
        $("#PoshtibanId").val(adminId);
        $("#poshtibanInfo").css({"display":"inline"});
        $("#bazaryabInfo").css({"display":"none"});
        $("#subListDashboardBtnPoshtiban").prop("disabled",false);
    }
    if(adminTypeId==6){
        
        $("#PoshtibanId").val(adminId);
        $("#poshtibanInfo").css({"display":"inline"});
        $("#bazaryabInfo").css({"display":"none"});
    }
    if(adminTypeId==7){
        
        $("#PoshtibanId").val(adminId);
        $("#poshtibanInfo").css({"display":"none"});
        $("#bazaryabInfo").css({"display":"none"});
    }
    

    $.ajax({
        method: "get",
        url: baseUrl + "/getAdminTodayInfo",
        data: {
            _token: "{{ csrf_token() }}",
            asn: adminId,
        },
        async: true,
        success: function (arrayed_result) {
            $("#adminCustomers").empty();
            moment.locale("en");
            let info = arrayed_result[0];
            let customers = arrayed_result[1];
            let peopels = arrayed_result[2];
            $("#loginTimeToday").text("");
            $("#adminName").text("");
            $("#countCommentsToday").text(0);
            $("#countFactorsToday").text(0);
            $("#countCustomersToday").text(0);
            $("#loginTimeToday").text(
                moment(peopels[0].loginTime, "YYYY/M/D HH:mm:ss")
                    .locale("fa")
                    .format("HH:mm:ss YYYY/M/D")
            );
            
            $("#adminName").text(info.name + " " + info.lastName);
            $("#countCommentsToday").text(peopels[0].countComments);
            $("#countFactorsToday").text(peopels[0].countFctors);
            $("#countCustomersToday").text(peopels[0].countCustomers);
            $("#adminCustomers").empty();
            customers.forEach((element, index) => {
                let maxHour = 0;
                let countFactor = 0;
                if (element.maxHour != null) {
                    maxHour = moment(element.maxHour, "YYYY/M/D HH:mm:ss")
                        .locale("fa")
                        .format("HH:mm:ss YYYY/M/D");
                }
                if (element.countFactor != null) {
                    countFactor = element.countFactor;
                }
                $("#adminCustomers").append(`<tr>
                                                <td>` +(index + 1) +`</td>
                                                <td>` +element.Name +`</td>
                                                <td>` + maxHour + `</td>
                                                <td>` +countFactor +`</td>
                                             </tr>`);
            });
        },
        error: function (data) {},
    });
}

$("#returnComment").on("click", () => {
    $.ajax({
        method: "get",
        url: baseUrl + "/viewReturnComment",
        data: {
            _token: "{{ csrf_token()}}",
            csn: $("#customerSn").val(),
        },
        async: true,
        success: function (arrayed_result) {
            $("#returnView").text(arrayed_result);

            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#returnViewComment").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });
            $("#returnViewComment").modal("show");
        },
        error: function (data) {},
    });
});

$("#changePositionForm").on("submit", function (event) {
    $.ajax({
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (data) {
            var map;
            if (L.DomUtil.get("map2") !== undefined) {
                L.DomUtil.get("map2")._leaflet_id = null;
            }
            //  var map = L.map('map2').setView([43.64701, -79.39425], 10);
            map = L.map("map2", { center: [35.70163, 51.39211], zoom: 10 });
            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                attribution: '<a href="https://osm.org/copyright">CRM</a>',
            }).addTo(map);

            var marker = {};
            data.forEach(function (item) {
                if (item.LatPers > 0 && item.LonPers > 0) {
                    var popup = new L.popup().setContent();
                    marker = L.marker([item.LonPers, item.LatPers], {
                        title: " تغییر موقعیت",
                        draggable: true,
                    })
                        .addTo(map)
                        .bindPopup(popup);

                    marker.on("dragend", () => {
                        let newposition = marker.getLatLng();
                        $("#newPosition").val(newposition);
                    });

                    let btn = document.createElement("a");
                    btn.innerText = "مشتری ";
                    // btn.setAttribute('href', "/Cardboard/cCode");
                    marker.bindPopup(btn, {
                        maxWidth: "200px",
                    });
                } else {
                    let defaultposition = [35.70163, 51.39211];

                    $("#newPosition").val(defaultposition);
                    marker = L.marker(defaultposition, {
                        title: "تعیین موقعیت",
                        draggable: true,
                    })
                        .addTo(map)
                        .bindPopup(popup);

                    marker.on("dragend", () => {
                        let newposition = marker.getLatLng();
                        $("#newPosition").val(newposition);
                    });
                }
            });

            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#changeCustomerLocation").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });

            $("#changeCustomerLocation").modal("show");
            setTimeout(function () {
                window.dispatchEvent(new Event("resize"));
            }, 500);
        },
        error: function (params) {
            alert("good luck");
        },
    });
    event.preventDefault();
});

$("#openkarabarDashboard").on("click", () => {
    $("#waitToDashboard").css("display", "flex");
    let asn = $("#adminSn").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/adminDashboard",
        data: {
            _token: "{{ csrf_token() }}",
            asn: asn,
        },
        async: true,
        success: function (arrayed_result) {
            moment.locale("en");
            let admin = arrayed_result[0];
            let info = arrayed_result[1];
            let history = arrayed_result[2];
            let customers = arrayed_result[3];
            let sumAllReturnedFactor = 0;
            if (info[0].totalReturnMoneyHds) {
                sumAllReturnedFactor = parseInt(
                    parseInt(info[0].totalReturnMoneyHds) / 10
                );
            }
            if (admin[0].minDate) {
                $("#assignCustomerDate").text(
                    moment(admin[0].minDate, "YYYY/M/D")
                        .locale("fa")
                        .format("YYYY/M/D")
                );
            } else {
                $("#assignCustomerDate").text("");
            }
            $("#countCustomer").text(admin[0].countPeopel);
            $("#countCustomerBought").text(info[0].boughtPeopelsCount);
            $("#countFactors").text(
                parseInt(info[0].countFactor) +
                    parseInt(info[0].countReturnFactor)
            );
            $("#allMoneyFactor").text(
                parseInt(
                    parseInt(info[0].totalMoneyHds / 10) +
                        parseInt(sumAllReturnedFactor)
                ).toLocaleString("en-us") + " تومن"
            );
            $("#lastMonthAllFactorMoney").text(
                parseInt(info[0].lastMonthFactorAllMoney / 10).toLocaleString(
                    "en-us"
                ) + " تومن"
            );
            if (info[0].lastMonthReturnedAllMoney) {
                $("#lastMonthAllFactorMoneyReturned").text(
                    parseInt(
                        info[0].lastMonthReturnedAllMoney / 10
                    ).toLocaleString("en-us") + " تومن"
                );
            } else {
                $("#lastMonthAllFactorMoneyReturned").text("0 تومن");
            }
            $("#countReturnedFactor").text(info[0].countReturnFactor);
            $("#allMoneyReturnedFactor").text(
                sumAllReturnedFactor.toLocaleString("en-us") + " تومن"
            );
            $("#notlogedIn").text(0);
            $("#comment").text(admin[0].discription);
            $("#adminNameModal").text(admin[0].name + " " + admin[0].lastName);
            $("#factorTable").empty();
            history.forEach((element, index) => {
                $("#factorTable").append(
                    `
            <tr>
            <td>` +
                        (index + 1) +
                        `</td>
            <td>` +
                        element.countPeople +
                        `</td>
            <td>` +
                        element.countBuyPeople +
                        `</td>
            <td>` +
                        element.countFactor +
                        `</td>
            <td>` +
                        parseInt(
                            element.lastMonthReturnedAllMoney / 10
                        ).toLocaleString("en-us") +
                        ` تومن` +
                        `</td>
            <td>` +
                        parseInt(element.factorAllMoney / 10).toLocaleString(
                            "en-us"
                        ) +
                        ` تومن` +
                        `</td>
             <td>` +
                        parseInt(element.lastMonthAllMoney / 10).toLocaleString(
                            "en-us"
                        ) +
                        ` تومن` +
                        `</td>
            <td>` +
                        (element.meanIncrease * 100).toLocaleString("en-us") +
                        ` </td>
            <td>` +
                        element.noCommentCust +
                        `</td>
            <td>` +
                        element.noDoneWork +
                        `</td>
            <td  onclick="showAdminComment(` +
                        admin[0].id +
                        `,'` +
                        element.timeStamp +
                        `')"><input name="factorId" style="display:none" type="radio" value="` +
                        admin[0].id +
                        `" /><i class="fa fa-eye" /> </td>
            </tr>
            `
                );
            });

            $("#lastMonthActions").empty();
            customers.forEach((element, index) => {
                $("#lastMonthActions").append(
                    `
            <tr>
            <td>` +
                        index +
                        1 +
                        `</td>
<td style="width:133px;">` +
                        element.countCustomers +
                        `</td>
            <td>` +
                        element.countAllFactor +
                        `</td>
            <td>` +
                        parseInt(
                            parseInt(
                                element.sumAllFactor / 10 +
                                    element.sumAllReturnedFactor / 10
                            )
                        ).toLocaleString("en-us") +
                        ` تومن` +
                        `</td>
            <td>` +
                        parseInt(
                            element.sumAllReturnedFactor / 10
                        ).toLocaleString("en-us") +
                        ` تومن` +
                        `</td>
            <td style="width:133px;">` +
                        parseInt(
                            parseInt(element.sumAllFactor / 10)
                        ).toLocaleString("en-us") +
                        ` تومن` +
                        `</td>
          </tr>
          `
                );
            });
            $("#waitToDashboard").css("display", "none");

            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#karbarAction").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });
            $("#karbarAction").modal("show");
        },
        error: function (data) {},
    });
});
function showAdminComment(id, timeStamp) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getAdminHistoryComment",
        data: {
            _token: "{{ csrf_token() }}",
            timeStamp: timeStamp,
            id: id,
        },
        async: true,
        success: function (arrayed_result) {
            // alert(arrayed_result.comment);
            $("#discription").text(arrayed_result.comment);

            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#readDiscription").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });
            $("#readDiscription").modal("show");
        },
        error: function (data) {},
    });
}

function showFactorDetails(element) {
    $(element).find("input:radio").prop("checked", true);
    let input = $(element).find("input:radio");
    $("tr").removeClass("selected");
    $(element).parent("tr").toggleClass("selected");
    alert(input.val())
    $.ajax({
        method: "get",
        url: baseUrl + "/getFactorDetail",
        data: {
            _token: "{{ csrf_token() }}",
            FactorSn: input.val(),
        },
        async: true,
        success: function (arrayed_result) {
            console.log(arrayed_result)
            let factor = arrayed_result[0];
            if (arrayed_result[0]) {
                $("#factorDate").text(factor.FactDate);
            }
            $("#customerNameFactor").text(factor.Name);
            $("#customerComenter").text(factor.Name);
            $("#customerAddressFactor").text(factor.peopeladdress);
            $("#customerPhoneFactor").text(factor.sabit);
            $("#factorSnFactor").text(factor.FactNo);
            $("#Admin1").text(factor.Name + " " + factor.Name);
            $("#productList").empty();

            arrayed_result.forEach((element, index) => {
                $("#productList").append(
                    `<tr>
            <td class="driveFactor">` +
                        (index + 1) +
                        `</td>
            <td>` +
                        element.GoodName +
                        ` </td>
            <td class="driveFactor">` +
                        element.Amount / 1 +
                        `</td>
            <td>` +
                        element.UName +
                        `</td>
            <td>` +
                        (element.Fi / 10).toLocaleString("en-us") +
                        `</td>
            <td style="width:111px;">` +
                        (
                            (element.Fi / 10) *
                            (element.Amount / 1)
                        ).toLocaleString("en-us") +
                        `</td>
            </tr>`
                );
            });

            $("#factorDate1").text(factor.FactDate);
            $("#customerNameFactor1").text(factor.Name);
            $("#customerComenter1").text(factor.Name);
            $("#customerAddressFactor1").text(factor.peopeladdress);
            $("#customerPhoneFactor1").text(factor.hamrah);
            $("#factorSnFactor1").text(factor.FactNo);
            $("#Admin1").text(factor.name + " " + factor.lastName);
            $("#productList1").empty();
            arrayed_result.forEach((element, index) => {
                $("#productList1").append(
                    `<tr>
            <td class="driveFactor">` +
                        (index + 1) +
                        `</td>
            <td>` +
                        element.GoodName +
                        ` </td>
            <td class="driveFactor">` +
                        element.Amount / 1 +
                        `</td>
            <td>` +
                        element.UName +
                        `</td>
            <td>` +
                        (element.Fi / 10).toLocaleString("en-us") +
                        `</td>
            <td style="width:111px;">` +
                        (
                            (element.Fi / 10) *
                            (element.Amount / 1)
                        ).toLocaleString("en-us") +
                        `</td>
            </tr>`
                );
            });

            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#viewFactorDetail").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });
            $("#viewFactorDetail").modal("show");
        },
        error: function (data) {},
    });
}

// searching the bargeri list
$("#bargerilist").on("keyup", () => {
    let searchTerm = $("#bargerilist").val();

    $.ajax({
        method: "get",
        url: baseUrl + "/crmDriverSearch",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm,
        },
        async: true,
        success: function (arrayed_result) {
            let searchingFactor = arrayed_result[0];
            $("#crmDriverBargeri").empty();
            searchingFactor.forEach((element, index) => {
                $("#crmDriverBargeri").append(
                    `
               <tr onclick="setBargiryStuff(this)">
                    <td>` +
                        (index + 1) +
                        `</td>
                    <td>` +
                        element.Name +
                        `</td>
                    <td class="address">` +
                        element.peopeladdress +
                        `</td>
                    <td>` +
                        element.PhoneStr +
                        `</td>
                    <td style="text-align: center;">
                            <a style="text-decoration:none;" target="_blank" href="https://maps.google.com/?q=` +
                        element.LonPers +
                        "," +
                        element.LatPers +
                        `"><i class="fas fa-map-marker-alt fa-1xl" style="color:#116bc7; "></i></a>
                    </td>
                    <td style="width:111px;" data-toggle="modal" data-target="#factorDeatials"><i class="fa fa-eye fa-1xl"> </i> </td>
                    <td class="choice"> <input class="customerList form-check-input" name="factorId" type="radio" value="` +
                        element.SerialNoHDS +
                        `"></td>
                </tr>
           `
                );
            });
        },
        error: function (data) {},
    });
});

function setAdminStuff(element, adminId, adminTypeId) {
    $(element).find("input:radio").prop("checked", true);
    let adminType = adminTypeId;
    let id = adminId;
    $("#asn").val(id);
    $("#emptyKarbarButton").val(id);
    $("#moveKarbarButton").val(id);
    $("#editAssingId").val(id);
    $("#editAssingBtn").prop("disabled", false);
    $("#adminTakerId").val(id);
    if ($("#emptyAdminBtn")) {
        $("#emptyAdminBtn").val(id);
    }

    $.ajax({
        method: "get",
        url: baseUrl + "/getAdminInfo",
        data: {
            _token: "{{ csrf_token() }}",
            id: id,
        },
        success: function (respond) {
            $("#adminDiscription").text("");
            $("#adminDiscription").text(respond[3].discription);
        },
        error: function (error) {},
    });

    if ((adminType > 1) & (adminType < 4)) {
        $("#customerContainer").css("display", "flex");
        $.ajax({
            method: "get",
            url: baseUrl + "/getCustomer",
            data: {
                _token: "{{ csrf_token() }}",
            },
            async: true,
            success: function (arrayed_result) {
                $("#allCustomer").empty();

                arrayed_result.forEach((element, index) => {
                    $("#allCustomer").append(
                        `
                <tr onclick="checkCheckBox(this,event)">
                    <td style="">` +
                            (index + 1) +
                            `</td>
                    <td style="">` +
                            element.PCode +
                            `</td>
                    <td>` +
                            element.Name +
                            `</td>
                    <td style="">
                    <input class="form-check-input" name="customerIDs[]" type="checkbox" value="` +
                            element.PSN +
                            `" id="customerId">
                    </td>
                </tr>`
                    );
                });
            },
            error: function (data) {},
        });
        $.ajax({
            method: "get",
            url: baseUrl + "/getAddedCustomer",
            data: {
                _token: "{{ csrf_token() }}",
                adminId: id,
            },
            async: true,
            success: function (arrayed_result) {
                if (arrayed_result.length > 0) {
                    $("#emptyKarbarButton").prop("disabled", false);
                    $("#moveKarbarButton").prop("disabled", false);
                    $("#deleteAdmin").prop("disabled", true);
                } else {
                    $("#emptyKarbarButton").prop("disabled", true);
                    $("#moveKarbarButton").prop("disabled", true);
                    $("#deleteAdmin").prop("disabled", false);
                }
                $("#addedCustomer").empty();
                arrayed_result.forEach((element, index) => {
                    $("#addedCustomer").append(
                        `
                <tr onclick="checkCheckBox(this,event)">
                    <td id="radif" style="width:55px;">` +
                            (index + 1) +
                            `</td>
                    <td id="mCode" style="width:115px;">` +
                            element.PCode +
                            `</td>
                    <td >` +
                            element.Name +
                            `</td>
                    <td style="width:50px;">
                        <input class="form-check-input" name="addedCustomerIDs[]" type="checkbox" value="` +
                            element.PSN +
                            `" id="kalaId">
                    </td>
                </tr>
            `
                    );
                });
            },
            error: function (data) {},
        });
    } else {
        $("#emptyKarbarButton").prop("disabled", true);
        $("#moveKarbarButton").prop("disabled", true);
        $("#deleteAdmin").prop("disabled", true);
        $("#customerContainer").css("display", "none");
    }
}
function setAdminListStuff(element, adminType, adminId, logedInId) {
    $(element).find("input:radio").prop("checked", true);
    let input = $(element).find("input:radio");
    let id = input.val();
    $("#asn").val(id);
    $("#AdminForAdd").val(id);
    if (adminType == 2) {
        $.ajax({
            method: "get",
            url: baseUrl + "/getAddedCustomer",
            data: {
                _token: "{{ csrf_token() }}",
                adminId: id,
            },
            async: true,
            success: function (arrayed_result) {
                if (arrayed_result.length > 0) {
                    $("#deleteSupporter").prop("disabled", true);
                    $("#setEditStuff").prop("disabled", true);
                    $("#deleteDriver").prop("disabled", true);
                    $("#deleteMarketer").prop("disabled", true);
                    $("#deleteAdmin").prop("disabled", true);
                    $("#editAdmin").prop("disabled", true);
                    $("#editDriver").prop("disabled", true);
                    $("#editSupporter").prop("disabled", false);
                    $("#editMarketer").prop("disabled", true);
                } else {
                    $("#deleteSupporter").prop("disabled", false);
                    $("#editSupporter").prop("disabled", false);
                    $("#deleteMarketer").prop("disabled", true);
                    $("#setEditStuff").prop("disabled", true);
                    $("#deleteDriver").prop("disabled", true);
                    $("#editAdmin").prop("disabled", true);
                    $("#editDriver").prop("disabled", true);
                    $("#editSupporter").prop("disabled", false);
                    $("#editMarketer").prop("disabled", true);
                }
            },
            error: function (data) {},
        });
    } else {
        if (adminType == 3) {
            $.ajax({
                method: "get",
                url: baseUrl + "/getAddedCustomer",
                data: {
                    _token: "{{ csrf_token() }}",
                    adminId: id,
                },
                async: true,
                success: function (arrayed_result) {
                    if (arrayed_result.length > 0) {
                        $("#deleteMarketer").prop("disabled", true);
                        $("#deleteSupporter").prop("disabled", true);
                        $("#setEditStuff").prop("disabled", true);
                        $("#deleteDriver").prop("disabled", true);
                        $("#deleteAdmin").prop("disabled", true);
                        $("#editAdmin").prop("disabled", true);
                        $("#editDriver").prop("disabled", true);
                        $("#editSupporter").prop("disabled", true);
                        $("#editMarketer").prop("disabled", false);
                    } else {
                        $("#deleteMarketer").prop("disabled", false);
                        $("#deleteSupporter").prop("disabled", true);
                        $("#setEditStuff").prop("disabled", true);
                        $("#deleteDriver").prop("disabled", true);
                        $("#deleteAdmin").prop("disabled", true);
                        $("#editAdmin").prop("disabled", true);
                        $("#editDriver").prop("disabled", true);
                        $("#editSupporter").prop("disabled", true);
                        $("#editMarketer").prop("disabled", false);
                    }
                },
                error: function (data) {},
            });
        } else {
            if (adminType == 1) {
                $("#deleteMarketer").prop("disabled", true);
                $("#deleteSupporter").prop("disabled", true);
                $("#setEditStuff").prop("disabled", true);
                $("#deleteDriver").prop("disabled", true);
                $("#deleteAdmin").prop("disabled", true);
                $("#editAdmin").prop("disabled", false);
                $("#editDriver").prop("disabled", true);
                $("#editSupporter").prop("disabled", true);
                $("#editMarketer").prop("disabled", true);
            } else {
                if (adminType == 5) {
                    if (logedInId == adminId) {
                        $("#deleteMarketer").prop("disabled", true);
                        $("#deleteAdmin").prop("disabled", true);
                        $("#editAdmin").prop("disabled", false);
                        $("#editDriver").prop("disabled", true);
                        $("#editSupporter").prop("disabled", true);
                        $("#editMarketer").prop("disabled", true);
                        $("#setEditStuff").prop("disabled", true);
                        $("#deleteDriver").prop("disabled", true);
                    } else {
                        $("#deleteMarketer").prop("disabled", true);
                        $("#deleteAdmin").prop("disabled", true);
                        $("#editAdmin").prop("disabled", true);
                        $("#editDriver").prop("disabled", true);
                        $("#editSupporter").prop("disabled", true);
                        $("#editMarketer").prop("disabled", true);
                        $("#setEditStuff").prop("disabled", true);
                        $("#deleteDriver").prop("disabled", true);
                    }
                } else {
                    $("#deleteMarketer").prop("disabled", true);
                    $("#deleteSupporter").prop("disabled", true);
                    $("#deleteAdmin").prop("disabled", true);
                    $("#deleteDriver").prop("disabled", false);
                    $("#editAdmin").prop("disabled", true);
                    $("#editDriver").prop("disabled", false);
                    $("#editSupporter").prop("disabled", true);
                    $("#editMarketer").prop("disabled", true);
                }
            }
        }
    }
}
$("#addMessageButton").on("click", () => {
    if (!$(".modal.in").length) {
        $(".modal-dialog").css({
            top: 0,
            left: 0,
        });
    }
    $("#userList").modal({
        backdrop: false,
        show: true,
    });

    $(".modal-dialog").draggable({
        handle: ".modal-header",
    });

    $("#userList").modal("show");
});
function setMessageStuff(element) {
    $(element).find("input:radio").prop("checked", true);
    let input = $(element).find("input:radio");
    let adminType = input.val().split("_")[1];
    let id = input.val().split("_")[0];
    $.ajax({
        method: "get",
        url: baseUrl + "/getAdminInfo",
        data: {
            _token: "{{ csrf_token() }}",
            id: id,
        },
        async: true,
        success: function (msg) {
            $("#sendTo").text(msg[3].name + " " + msg[3].lastName);
            $("#getterId").val(msg[3].id);
            moment.locale("en");
            let sended = msg[0];
            let myId = msg[2];
            let appositId = msg[1];
            $("#messageList").empty();
            sended.forEach((element, index) => {
                let showDate = "";
                if (element.diffDate > 0) {
                    showDate =
                        ` ` +
                        moment(element.messageDate, "YYYY/M/D HH:mm:ss")
                            .locale("fa")
                            .format("YYYY/M/D") +
                        ` `;
                }
                if (appositId == element.getterId) {
                    $("#messageList").append(
                        `` +
                            showDate +
                            `<div class="d-flex flex-row justify-content-start mb-1">
                <img src="resources/assets/images/admins/` +
                            myId +
                            `.jpg" alt="avatar 1" style="width: 50px; height: 50px; border-radius:100%";">
                <div class="p-2 ms-2" style="border-radius:10px; background-color: rgba(78, 192, 229, 0.2);">
                    <p class="small" style="font-size:0.9rem;"> <span style="color:gray; font-size:10px; padding-bottom:30px; font-style:italic; margin-left:10px;"> ` +
                            moment(element.messageDate, "YYYY/M/D HH:mm:ss")
                                .locale("fa")
                                .format("HH:mm:ss") +
                            ` </span> ` +
                            element.messageContent +
                            `</p>
                </div>
            </div> 
            `
                    );
                } else {
                    $("#messageList").append(
                        `` +
                            showDate +
                            `<div class="d-flex flex-row justify-content-end mb-2">
                    <div class="p-2 me-2 border" id="replayDiv'.$replay->id.'" style="border-radius: 15px; background-color: #fbfbfb;">
                    <p class="small" style="font-size:0.9rem;"> ` +
                            element.messageContent +
                            ` <span style="color:gray; font-size:10px; padding-bottom:30px; font-style:italic; margin-left:10px;"> ` +
                            moment(element.messageDate, "YYYY/M/D HH:mm:ss")
                                .locale("fa")
                                .format("HH:mm:ss") +
                            ` </span></p>
                    </div>
                    <img src="resources/assets/images/admins/` +
                            appositId +
                            `.jpg" alt="avatar 1" style="width: 50px; height: 50px; border-radius:100%;">
                </div>`
                    );
                }
            });

            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#addMessage").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });

            $("#addMessage").modal("show");
            $("#userList").modal("hide");
        },
        error: function (err) {},
    });
}
$("#addMessageForm").submit(function (e) {
    $.ajax({
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (arrayed_result) {
            $("#sendTo").text(msg[3].name + " " + msg[3].lastName);
            $("#getterId").val(msg[3].id);
            moment.locale("en");
            let sended = msg[0];
            let myId = msg[2];
            let appositId = msg[1];
            $("#messageList").empty();
            sended.forEach((element, index) => {
                let showDate = "";
                if (element.diffDate > 0) {
                    showDate =
                        ` ` +
                        moment(element.messageDate, "YYYY/M/D HH:mm:ss")
                            .locale("fa")
                            .format("YYYY/M/D") +
                        ` `;
                }
                if (appositId == element.getterId) {
                    $("#messageList").append(
                        `` +
                            showDate +
                            `<div class="d-flex flex-row justify-content-start mb-1">
                <img src="resources/assets/images/admins/` +
                            myId +
                            `.jpg" alt="avatar 1" style="width: 50px; height: 50px; border-radius:100%";">
                <div class="p-2 ms-2" style="border-radius:10px; background-color: rgba(78, 192, 229, 0.2);">
                    <p class="small" style="font-size:0.9rem;"> <span style="color:gray; font-size:10px; padding-bottom:30px; font-style:italic; margin-left:10px;"> ` +
                            moment(element.messageDate, "YYYY/M/D HH:mm:ss")
                                .locale("fa")
                                .format("HH:mm:ss") +
                            ` </span> ` +
                            element.messageContent +
                            `</p>
                </div>
            </div> 
            `
                    );
                } else {
                    $("#messageList").append(
                        `` +
                            showDate +
                            `<div class="d-flex flex-row justify-content-end mb-2">
                    <div class="p-2 me-2 border" id="replayDiv'.$replay->id.'" style="border-radius: 15px; background-color: #fbfbfb;">
                    <p class="small" style="font-size:0.9rem;"> ` +
                            element.messageContent +
                            ` <span style="color:gray; font-size:10px; padding-bottom:30px; font-style:italic; margin-left:10px;"> ` +
                            moment(element.messageDate, "YYYY/M/D HH:mm:ss")
                                .locale("fa")
                                .format("HH:mm:ss") +
                            ` </span></p>
                    </div>
                    <img src="resources/assets/images/admins/` +
                            appositId +
                            `.jpg" alt="avatar 1" style="width: 50px; height: 50px; border-radius:100%;">
                </div>`
                    );
                }
            });
            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#addMessage").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });
            $("#addMessage").modal("show");
            $("#userList").modal("hide");
        },
        error: () => {
            alert("bad");
        },
    });
    e.preventDefault();
});
function setReadMessageStuff(element) {
    $(element).find("input:radio").prop("checked", true);
    let input = $(element).find("input:radio");
    $("#senderId").val(input.val());
    $("#getterIdD").val(input.val());
    sendId = $("#senderId").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/getDiscusstion",
        data: {
            _token: "{{ csrf_token() }}",
            sendId: sendId,
        },
        async: true,
        success: function (arrayed_result) {
            let sended = arrayed_result[0];
            let appositId = arrayed_result[1];
            let myId = arrayed_result[2];
            moment.locale("en");
            $("#sendedMessages").empty();
            $("#recivedMessages").empty();
            $("#messageDiscusstion").empty();
            let prevDate;
            sended.forEach((element, index) => {
                let showDate = "";
                if (element.diffDate > 0) {
                    showDate =
                        ` ` +
                        moment(element.messageDate, "YYYY/M/D HH:mm:ss")
                            .locale("fa")
                            .format("YYYY/M/D") +
                        ` `;
                }
                if (appositId == element.getterId) {
                    $("#messageDiscusstion").append(
                        `` +
                            showDate +
                            `<div class="d-flex flex-row justify-content-start mb-1">
                <img src="resources/assets/images/admins/` +
                            myId +
                            `.jpg" alt="avatar 1" style="width: 50px; height: 50px; border-radius:100%">
                <div class="p-2 ms-2" style="border-radius:10px; height:40px; background-color: rgba(78, 192, 229, 0.2);">
                    <p class="small" style="font-size:0.9rem;"> <span style="color:gray; font-size:10px; padding-bottom:30px; font-style:italic; margin-left:10px;"> ` +
                            moment(element.messageDate, "YYYY/M/D HH:mm:ss")
                                .locale("fa")
                                .format("HH:mm:ss") +
                            ` </span> ` +
                            element.messageContent +
                            `</p>
    
                </div>
            </div>`
                    );
                } else {
                    $("#messageDiscusstion").append(
                        `` +
                            showDate +
                            `<div class="d-flex flex-row justify-content-end mb-2">
                    <div class="p-2 me-2 border" id="replayDiv'.$replay->id.'" style="border-radius: 15px; height:40px; background-color: #fbfbfb;">
                    <p class="small" style="font-size:0.9rem;">  ` +
                            element.messageContent +
                            `<span style="color:gray; font-size:10px; padding-bottom:30px; font-style:italic; margin-left:10px;"> ` +
                            moment(element.messageDate, "YYYY/M/D HH:mm:ss")
                                .locale("fa")
                                .format("HH:mm:ss") +
                            `</span> </p>
                     
                    </div>
                    <img src="resources/assets/images/admins/` +
                            appositId +
                            `.jpg" alt="avatar 1" style="width: 50px; height: 50px; border-radius:100%">
                    
                </div>`
                    );
                }
                prevDate = element.messageDate;
            });

            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#readComments").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });
            $("#readComments").modal("show");
        },
        error: function (data) {},
    });
}

$("#addDisscusstionForm").submit(function (e) {
    $.ajax({
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (arrayed_result) {
            $("#messageArea").val("");
            let sended = arrayed_result[0];
            let myId = arrayed_result[2];
            let appositId = arrayed_result[1];
            $("#sendedMessages").empty();
            $("#recivedMessages").empty();
            $("#messageDiscusstion").empty();
            sended.forEach((element, index) => {
                if (appositId == element.getterId) {
                    $("#messageDiscusstion").append(
                        `<div class="d-flex flex-row justify-content-start mb-1">
                <img src="resources/assets/images/admins/` +
                            myId +
                            `.jpg" alt="avatar 1" style="width: 45px; height: 100%;">
                <div class="p-2 ms-2" style="border-radius:10px; height:40px; background-color: rgba(78, 192, 229, 0.2);">
                    <p class="small" style="font-size:0.9rem;"> ` +
                            element.messageContent +
                            `</p>
                </div>
            </div>`
                    );
                } else {
                    $("#messageDiscusstion").append(
                        `<div class="d-flex flex-row justify-content-end mb-2">
                    <div class="p-2 me-2 border" id="replayDiv'.$replay->id.'" style="border-radius: 15px; height:40px; background-color: #fbfbfb;">
                    <p class="small" style="font-size:0.9rem;"> ` +
                            element.messageContent +
                            `</p>
                    </div>
                    <img src="resources/assets/images/admins/` +
                            appositId +
                            `.jpg" alt="avatar 1" style="width: 45px; height: 100%;">
                </div>`
                    );
                }
            });
            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#readComments").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });
            $("#readComments").modal("show");
        },
        error: () => {
            alert("bad");
        },
    });
    e.preventDefault();
});
function checkCheckBox(element, event) {
    if (event.target.type == "checkbox") {
        e.stopPropagation();
    } else {
        if ($(element).find("input:checkbox").prop("disabled") == false) {
            if ($(element).find("input:checkbox").prop("checked") == false) {
                $(element).find("input:checkbox").prop("checked", true);
            } else {
                $(element).find("input:checkbox").prop("checked", false);
                $(element).find("td.selected").removeClass("selected");
            }
        }
    }
    if ($("#adminTasviyahBtn")) {
        $("#adminTasviyahBtn").prop("disabled", false);
    }
}
$(".selectAllFromTop").on("change", (e) => {
    if ($(e.target).is(":checked")) {
        var table = $(e.target).closest("table");
        if (!$("td input:checkbox", table).is(":disabled")) {
            $("td input:checkbox", table).prop("checked", true);
        }
    } else {
        var table = $(e.target).closest("table");
        $("td input:checkbox", table).prop("checked", false);
    }
});

$("#takhsisEditRightSideForm").on("submit", function (e) {
    e.preventDefault();
    $.ajax({
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (arrayed_result) {
            console.log(arrayed_result);
            $("#allCustomer").empty();

            arrayed_result.forEach((element, index) => {
                $("#allCustomer").append(
                    `
            <tr onclick="checkCheckBox(this,event)">
                <td style="">` +
                        (index + 1) +
                        `</td>
                <td style="">` +
                        element.NameRec +
                        `</td>
                <td>` +
                        element.Name +
                        `</td>
                <td style="">
                <input class="form-check-input" name="customerIDs[]" type="checkbox" value="` +
                        element.PSN +
                        `" id="customerId">
                </td>
            </tr>`
                );
            });
        },
        error: function (error) {},
    });
});

$("#addCustomerToAdmin").on("click", () => {
    swal({
        title: "اخطار!",
        text: "آیا می خواهید مشتریان اضافه شوند؟",
        icon: "warning",
        buttons: true,
    }).then(function (willAdd) {
        if (willAdd) {
            $("#transferLoader").css("display", "block");
            $("#selectAllTopRight").prop("checked", false);
            let adminId = $("#AdminForAdd").val();
            var customerID = [];
            $('input[name="customerIDs[]"]:checked').map(function () {
                customerID.push($(this).val());
            });
            $.ajax({
                method: "get",
                url: baseUrl + "/AddCustomerToAdmin",
                data: {
                    _token: "{{ csrf_token() }}",
                    adminId: adminId,
                    customerIDs: customerID,
                },
                async: true,
                success: function (arrayed_result) {
                    $("#transferLoader").css("display", "none");
                    $("#addedCustomer").empty();
                    arrayed_result.forEach((element, index) => {
                        $("#addedCustomer").append(
                            `
                <tr  onclick="checkCheckBox(this,event)">
                    <td>` +
                                (index + 1) +
                                `</td>
                    <td>` +
                                element.PCode +
                                `</td>
                    <td>` +
                                element.Name +
                                `</td>
                    <td>
                    <input class="form-check-input" name="addedCustomerIDs[]" type="checkbox" value="` +
                                element.PSN +
                                `">
                    </td>
                </tr>
            `
                        );
                    });
                },
                error: function (data) {},
            });
            $.ajax({
                method: "get",
                url: baseUrl + "/getCustomer",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                async: true,
                success: function (arrayed_result) {
                    $("#allCustomer").empty();
                    arrayed_result.forEach((element, index) => {
                        $("#allCustomer").append(
                            `
            <tr  onclick="checkCheckBox(this,event)">
                <td>` +
                                (index + 1) +
                                `</td>
                <td>` +
                                element.PCode +
                                `</td>
                <td>` +
                                element.Name +
                                `</td>
                <td>
                <input class="form-check-input" name="customerIDs[]" type="checkbox" value="` +
                                element.PSN +
                                `" id="customerId">
                </td>
            </tr>
        `
                        );
                    });
                },
                error: function (data) {},
            });
        } else {
        }
    });
});

$("#addCustomerToAdminOp").on("click", () => {
    swal({
        title: "اخطار!",
        text: "آیا می خواهید مشتریان اضافه شوند؟",
        icon: "warning",
        buttons: true,
    }).then(function (willAdd) {
        if (willAdd) {
            $("#transferLoader").css("display", "block");
            $("#selectAllTopRight").prop("checked", false);
            let adminId = $("#takhsisToAdminBtn").val();
            var customerID = [];
            $('input[name="customerIDs[]"]:checked').map(function () {
                customerID.push($(this).val());
            });
            $.ajax({
                method: "get",
                url: baseUrl + "/AddCustomerToAdmin",
                data: {
                    _token: "{{ csrf_token() }}",
                    adminId: adminId,
                    customerIDs: customerID,
                },
                async: true,
                success: function (arrayed_result) {
                    $("#transferLoader").css("display", "none");
                    $("#addedCustomer").empty();
                    arrayed_result.forEach((element, index) => {
                        $("#addedCustomer").append(
                            `
                <tr  onclick="checkCheckBox(this,event)">
                    <td>` +
                                (index + 1) +
                                `</td>
                    <td>` +
                                element.PCode +
                                `</td>
                    <td>` +
                                element.Name +
                                `</td>
                    <td>
                    <input class="form-check-input" name="addedCustomerIDs[]" type="checkbox" value="` +
                                element.PSN +
                                `">
                    </td>
                </tr>
            `
                        );
                    });
                },
                error: function (data) {},
            });
            $.ajax({
                method: "get",
                url: baseUrl + "/getCustomer",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                async: true,
                success: function (arrayed_result) {
                    $("#allCustomer").empty();
                    arrayed_result.forEach((element, index) => {
                        $("#allCustomer").append(
                            `
            <tr  onclick="checkCheckBox(this,event)">
                <td>` +
                                (index + 1) +
                                `</td>
                <td>` +
                                element.PCode +
                                `</td>
                <td>` +
                                element.Name +
                                `</td>
                <td>
                <input class="form-check-input" name="customerIDs[]" type="checkbox" value="` +
                                element.PSN +
                                `" id="customerId">
                </td>
            </tr>
        `
                        );
                    });
                },
                error: function (data) {},
            });
        } else {
        }
    });
});

$("#removeCustomerFromAdmin").on("click", () => {
    var customerIDs = [];
    adminId = $("#AdminForAdd").val();
    swal({
        title: "اخطار!",
        text: "آیا می خواهید مشتریان حذف شوند؟",
        icon: "warning",
        buttons: true,
    }).then(function (willDelete) {
        if (willDelete) {
            $("#selectAllTopLeft").prop("checked", false);
            $("#transferLoader").css("display", "block");
            adminId = $("#AdminForAdd").val();
            $('input[name="addedCustomerIDs[]"]:checked').map(function () {
                customerIDs.push($(this).val());
            });
            $.ajax({
                method: "get",
                url: baseUrl + "/RemoveCustomerFromAdmin",
                data: {
                    _token: "{{ csrf_token() }}",
                    adminId: adminId,
                    customerIDs: customerIDs,
                },
                async: true,
                success: function (arrayed_result) {
                    if (arrayed_result != 1) {
                        $("#addedCustomer").empty();
                        arrayed_result.forEach((element, index) => {
                            $("#addedCustomer").append(
                                `
            <tr  onclick="checkCheckBox(this,event)">
                <td>` +
                                    (index + 1) +
                                    `</td>
                <td>` +
                                    element.PCode +
                                    `</td>
                <td>` +
                                    element.Name +
                                    `</td>
                <td>
                <input class="form-check-input" name="addedCustomerIDs[]" type="checkbox" value="` +
                                    element.PSN +
                                    `">
                </td>
            </tr>
        `
                            );
                        });
                    } else {
                        swal({
                            title: "اخطار!",
                            text: "تاریخچه ثبت نمی شود، می خواهید انجام شود؟",
                            icon: "warning",
                            buttons: true,
                        }).then(function (willDelete) {
                            if (willDelete) {
                                $("#selectAllTopLeft").prop("checked", false);
                                $("#transferLoader").css("display", "block");
                                $.ajax({
                                    method: "get",
                                    url: baseUrl + "/RemoveCustomerAndEmpty",
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        adminId: adminId,
                                        customerIDs: customerIDs,
                                    },
                                    async: true,
                                    success: function (arrayed_result) {
                                        $("#addedCustomer").empty();
                                        arrayed_result.forEach(
                                            (element, index) => {
                                                $("#addedCustomer").append(
                                                    `
                    <tr  onclick="checkCheckBox(this,event)">
                        <td>` +
                                                        (index + 1) +
                                                        `</td>
                        <td>` +
                                                        element.PCode +
                                                        `</td>
                        <td>` +
                                                        element.Name +
                                                        `</td>
                        <td>
                        <input class="form-check-input" name="addedCustomerIDs[]" type="checkbox" value="` +
                                                        element.PSN +
                                                        `">
                        </td>
                    </tr>
                `
                                                );
                                            }
                                        );
                                        $("#transferLoader").css(
                                            "display",
                                            "none"
                                        );
                                    },
                                    error: function (data) {},
                                });
                            }
                        });
                    }
                    $("#transferLoader").css("display", "none");
                },
                error: function (data) {},
            });
        }
    });
});

$("#removeCustomerFromAdminOp").on("click", () => {
    var customerIDs = [];
    adminId = $("#takhsisToAdminBtn").val();
    swal({
        title: "اخطار!",
        text: "آیا می خواهید مشتریان حذف شوند؟",
        icon: "warning",
        buttons: true,
    }).then(function (willDelete) {
        if (willDelete) {
            $("#selectAllTopLeft").prop("checked", false);
            $("#transferLoader").css("display", "block");
            adminId = adminId;
            $('input[name="addedCustomerIDs[]"]:checked').map(function () {
                customerIDs.push($(this).val());
            });
            $.ajax({
                method: "get",
                url: baseUrl + "/RemoveCustomerFromAdmin",
                data: {
                    _token: "{{ csrf_token() }}",
                    adminId: adminId,
                    customerIDs: customerIDs,
                },
                async: true,
                success: function (arrayed_result) {
                    if (arrayed_result != 1) {
                        $("#addedCustomer").empty();
                        arrayed_result.forEach((element, index) => {
                            $("#addedCustomer").append(
                                `
                <tr  onclick="checkCheckBox(this,event)">
                    <td>` +
                                    (index + 1) +
                                    `</td>
                    <td>` +
                                    element.PCode +
                                    `</td>
                    <td>` +
                                    element.Name +
                                    `</td>
                    <td>
                    <input class="form-check-input" name="addedCustomerIDs[]" type="checkbox" value="` +
                                    element.PSN +
                                    `">
                    </td>
                </tr>
            `
                            );
                        });
                    } else {
                        swal({
                            title: "اخطار!",
                            text: "تاریخچه ثبت نمی شود، می خواهید انجام شود؟",
                            icon: "warning",
                            buttons: true,
                        }).then(function (willDelete) {
                            if (willDelete) {
                                $("#selectAllTopLeft").prop("checked", false);
                                $("#transferLoader").css("display", "block");
                                $.ajax({
                                    method: "get",
                                    url: baseUrl + "/RemoveCustomerAndEmpty",
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        adminId: adminId,
                                        customerIDs: customerIDs,
                                    },
                                    async: true,
                                    success: function (arrayed_result) {
                                        $("#addedCustomer").empty();
                                        arrayed_result.forEach(
                                            (element, index) => {
                                                $("#addedCustomer").append(
                                                    `
                        <tr  onclick="checkCheckBox(this,event)">
                            <td>` +
                                                        (index + 1) +
                                                        `</td>
                            <td>` +
                                                        element.PCode +
                                                        `</td>
                            <td>` +
                                                        element.Name +
                                                        `</td>
                            <td>
                            <input class="form-check-input" name="addedCustomerIDs[]" type="checkbox" value="` +
                                                        element.PSN +
                                                        `">
                            </td>
                        </tr>
                    `
                                                );
                                            }
                                        );
                                        $("#transferLoader").css(
                                            "display",
                                            "none"
                                        );
                                    },
                                    error: function (data) {},
                                });
                            }
                        });
                    }
                    $("#transferLoader").css("display", "none");
                },
                error: function (data) {},
            });
            //برای نمایش مشتریان بدون کاربر سمت راست
            $.ajax({
                method: "get",
                url: baseUrl + "/getCustomer",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                async: true,
                success: function (arrayed_result) {
                    $("#allCustomer").empty();

                    arrayed_result.forEach((element, index) => {
                        $("#allCustomer").append(
                            `
            <tr onclick="checkCheckBox(this,event)">
                <td style="">` +
                                (index + 1) +
                                `</td>
                <td style="">` +
                                element.PCode +
                                `</td>
                <td>` +
                                element.Name +
                                `</td>
                <td style="">
                <input class="form-check-input" name="customerIDs[]" type="checkbox" value="` +
                                element.PSN +
                                `" id="customerId">
                </td>
            </tr>
        `
                        );
                    });
                },
                error: function (data) {},
            });
        }
    });
});

$("#searchAddedCity").on("change", function () {
    $.ajax({
        method: "get",
        url: baseUrl + "/searchAssignRegion",
        data: {
            _token: "{{ csrf_token() }}",
            cityId: $("#searchAddedCity").val(),
        },
        async: true,
        success: function (arrayed_result) {
            $("#searchAddedMantagheh").empty();
            arrayed_result.forEach((element, index) => {
                $("#searchAddedMantagheh").append(
                    `
            <option value="` +
                        element.SnMNM +
                        `">` +
                        element.NameRec +
                        `</option>
        `
                );
            });
        },
        error: function (data) {},
    });
});
$("#searchByCity").on("change", () => {
    $.ajax({
        method: "get",
        url: baseUrl + "/searchAssignRegion",
        data: {
            _token: "{{ csrf_token() }}",
            cityId: $("#searchByCity").val(),
        },
        async: true,
        success: function (arrayed_result) {
            $("#searchByMantagheh").empty();
            arrayed_result.forEach((element, index) => {
                $("#searchByMantagheh").append(
                    `
            <option value="` +
                        element.SnMNM +
                        `">` +
                        element.NameRec +
                        `</option>
        `
                );
            });
        },
        error: function (data) {},
    });
});

$("#searchAlarmByCity").on("change", () => {
    $.ajax({
        method: "get",
        url: baseUrl + "/searchAssignRegion",
        data: {
            _token: "{{ csrf_token() }}",
            cityId: $("#searchAlarmByCity").val(),
        },
        async: true,
        success: function (arrayed_result) {
            $("#searchAlarmByMantagheh").empty();
            arrayed_result.forEach((element, index) => {
                $("#searchAlarmByMantagheh").append(
                    `
            <option value="` +
                        element.SnMNM +
                        `">` +
                        element.NameRec +
                        `</option>
        `
                );
            });
        },
        error: function (data) {},
    });
});

function getAlarmHistory(history) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getAlarmsHistory",
        data: {
            _token: "{{ csrf_token() }}",
            history:history
        },
        async: true,
        success: function (msg) {
            $("#alarmsbody").empty();
            msg.forEach((element,index)=>{
                $("#alarmsbody").append(`<tr onClick="setAlarmCustomerStuff(this,`+element.id+`)">
                                            <td >`+(index+1)+`</td>
                                            <td style="width:111px">` +
                            moment(element.TimeStamp, "YYYY/M/D")
                                .locale("fa")
                                .format("YYYY/M/D") +`</td>
                                            <td>`+element.Name+`</td>
                                            <td>`+element.PhoneStr+`</td>
                                            <td style="width:99px">`+element.countCycle+`</td>
                                            <td style="width:77px">`+element.NameRec+`</td>
                                            <td style="width:66px">`+element.assignedDays+`</td>
                                            <td  >`+element.FactDate+`</td>
                                            <td style="width:111px; color:red">`+element.alarmDate+`</td>
                                            <td style="width:166px">`+element.poshtibanName+` `+element.poshtibanLastName+`</td>
                                            <td><input class="customerList form-check-input" name="customerId" type="radio" value="`+element.PSN+`_`+element.adminSn+`_`+element.SerialNoHDS+`"></td>
                                        </tr>`);
            })
        }
        ,error:function(error){

        }
    });
}

$("#filterAlarmsForm").on("submit",function(e){
e.preventDefault();
$.ajax({
    url: $(this).attr("action"),
    data: $(this).serialize(),
    success: function (msg) {
        if(!$("#customerWithOutAlarm").is(":checked")){
            $("#alarmedCustomers").css("display","block");
            $("#unAlarmedCustomers").css("display","none");
            $("#alarmsbody").empty();
            msg.forEach((element,index)=>{
                $("#alarmsbody").append(`<tr onClick="setAlarmCustomerStuff(this,`+element.id+`)">
                                            <td >`+(index+1)+`</td>
                                            <td style="width:111px">` +
                                            moment(element.TimeStamp, "YYYY/M/D HH:mm:ss")
                                                .locale("fa")
                                                .format("YYYY/M/D") +`</td>
                                            
                                            <td>`+element.Name+`</td>
                                            <td>`+element.PhoneStr+`</td>
                                            <td style="width:99px">`+element.countCycle+`</td>
                                            <td style="width:77px">`+element.NameRec+`</td>
                                            <td style="width:66px">`+element.assignedDays+`</td>
                                            <td style="width:111px;">`+element.FactDate+`</td>
                                            <td style="width:111px; color:red">`+element.alarmDate+`</td>
                                            <td style="width:166px">`+element.poshtibanName+` `+element.poshtibanLastName+`</td>
                                            <td><input class="customerList form-check-input" name="customerId" type="radio" value="`+element.PSN+`_`+element.adminSn+`_`+element.SerialNoHDS+`"></td>
                                        </tr>`);
            })
        }else{
            $("#alarmedCustomers").css("display","none");
            
            $("#unalarmsbody").empty();
            msg.forEach((element,index)=>{
                $("#unalarmsbody").append(`<tr  onclick="setUnAlarmStuff(this,`+element.PSN+`,`+element.adminId+`)">
                <td >`+(index+1)+`</td>
                <td>`+element.Name+`</td>
                <td>`+element.PCode+`</td>
                <td>`+element.PhoneStr+`</td>
                <td style="width:77px">`+element.NameRec+`</td>
                <td style="width:111px;">`+element.FactDate+`</td>
                <td style="width:166px">`+element.adminName+`</td>
                <td><input class="customerList form-check-input" name="customerId" type="radio" value="`+element.PSN+`_`+element.adminId+`_`+element.SerialNoHDS+`"></td>
            </tr>`);
            })
        }
    },
    error:function(error){

    }
});
})

$("#orderAlarms").on("change",function(){
    searchTerm=$("#searchAlarmName").val();
    snMantagheh=$("#searchAlarmByMantagheh").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/orderAlarms",
        data: {
            _token: "{{ csrf_token() }}",
            baseName:$("#orderAlarms").val(),
            searchTerm:searchTerm,
            snMantagheh:snMantagheh
        },
        async: true,
        success: function (msg) {
            $("#alarmsbody").empty();
            msg.forEach((element,index)=>{
                $("#alarmsbody").append(`<tr onClick="setAlarmCustomerStuff(this,`+element.id+`)">
                                            <td >`+(index+1)+`</td>
                                            <td style="width:111px">` +
                                            moment(element.TimeStamp, "YYYY/M/D HH:mm:ss")
                                                .locale("fa")
                                                .format("YYYY/M/D") +`</td>
                                            
                                            <td>`+element.Name+`</td>
                                            <td>`+element.PhoneStr+`</td>
                                            <td style="width:99px">`+element.countCycle+`</td>
                                            <td style="width:77px">`+element.NameRec+`</td>
                                            <td style="width:66px">`+element.assignedDays+`</td>
                                            <td>`+element.FactDate+`</td>
                                            <td style="width:111px; color:red">`+element.alarmDate+`</td>
                                            <td style="width:166px">`+element.poshtibanName+` `+element.poshtibanLastName+`</td>
                                            <td><input class="customerList form-check-input" name="customerId" type="radio" value="`+element.PSN+`_`+element.adminSn+`_`+element.SerialNoHDS+`"></td>
                                        </tr>`);
            })
        }
        ,error:function(error){

        }
    });
})

$("#orderUnAlarms").on("change",function(){
    searchTerm=$("#searchAlarmName").val();
    snMantagheh=$("#searchAlarmByMantagheh").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/orderUnAlarms",
        data: {
            _token: "{{ csrf_token() }}",
            baseName:$("#orderUnAlarms").val(),
            searchTerm:searchTerm,
            snMantagheh:snMantagheh
        },
        async: true,
        success: function (msg) {
            $("#unalarmsbody").empty();
            msg.forEach((element,index)=>{
                $("#unalarmsbody").append(` <tr  onclick="setUnAlarmStuff(this,`+element.PSN+`,`+element.adminId+`)">
                                                <td >`+(index+1)+`</td>
                                                <td>`+element.Name+`</td>
                                                <td>`+element.PCode+`</td>
                                                <td>`+element.PhoneStr+`</td>
                                                <td style="width:77px">`+element.NameRec+`</td>
                                                <td style="width:111px;">`+element.FactDate+`</td>
                                                <td style="width:166px">`+element.adminName+`</td>
                                                <td><input class="customerList form-check-input" name="customerId" type="radio" value="`+element.PSN+`_`+element.adminId+`_`+element.SerialNoHDS+`"></td>
                                            </tr>`);
            });
        }
        ,error:function(error){

        }
    });
});

function setUnAlarmStuff(element,customerSn,adminId){
    $("tr").removeClass("selected");
    $(element).toggleClass("selected");
    $("#customerSn").val(customerSn);
    $("#adminSn").val(adminId);
    $(".enableBtn").prop("disabled",false);
}

function getUnAlarmHistory(history) {
    
    $.ajax({
        method: "get",
        url: baseUrl + "/getUnAlarmHistory",
        data: {
            _token: "{{ csrf_token() }}",
            history:history
        },
        async: true,
        success: function (msg) {
            $("#unalarmsbody").empty();
            msg.forEach((element,index)=>{
                $("#unalarmsbody").append(`<tr onclick="setUnAlarmStuff(this,`+element.PSN+`,`+element.adminId+`)">
                                                <td >`+(index+1)+`</td>
                                                <td>`+element.Name+`</td>
                                                <td>`+element.PCode+`</td>
                                                <td>`+element.PhoneStr+`</td>
                                                <td style="width:77px">`+element.NameRec+`</td>
                                                <td style="width:111px;">`+element.FactDate+`</td>
                                                <td style="width:166px">`+element.adminName+`</td>
                                                <td><input class="customerList form-check-input" name="customerId" type="radio" value="`+element.PSN+`_`+element.adminId+`_`+element.SerialNoHDS+`"></td>
                                            </tr>`);
            });
        }
        ,error:function(error){

        }
    });

}

$("#searchCity").on("change", function () {
    $.ajax({
        method: "get",
        url: baseUrl + "/searchAssignRegion",
        data: {
            _token: "{{ csrf_token() }}",
            cityId: $("#searchCity").val(),
        },
        async: true,
        success: function (arrayed_result) {
            $("#activeOrInActive").prop("disabled", false);
            $("#searchMantagheh").empty();
            arrayed_result.forEach((element, index) => {
                $("#searchMantagheh").append(
                    `
            <option value="` +
                        element.SnMNM +
                        `">` +
                        element.NameRec +
                        `</option>
        `
                );
            });
        },
        error: function (data) {},
    });
});
$("#snNahiyehE").on("change", function () {
    $.ajax({
        method: "get",
        url: baseUrl + "/searchAssignRegion",
        data: {
            _token: "{{ csrf_token() }}",
            cityId: $("#snNahiyehE").val(),
        },
        async: true,
        success: function (arrayed_result) {
            $("#snMantaghehE").empty();
            arrayed_result.forEach((element, index) => {
                $("#snMantaghehE").append(
                    `
            <option value="` +
                        element.SnMNM +
                        `">` +
                        element.NameRec +
                        `</option>
        `
                );
            });
        },
        error: function (data) {},
    });
});
$("#findMantaghehByCity").on("change", () => {
    $.ajax({
        method: "get",
        url: baseUrl + "/searchRegion",
        data: {
            _token: "{{ csrf_token() }}",
            cityId: $("#findMantaghehByCity").val(),
        },
        async: true,
        success: function (arrayed_result) {
            $("#searchCustomerByMantagheh").empty();
            $("#searchCustomerByMantagheh").append(
                `<option value="0">همه</option>`
            );
            arrayed_result.forEach((element, index) => {
                $("#searchCustomerByMantagheh").append(
                    `
            <option value="` +
                        element.SnMNM +
                        `">` +
                        element.NameRec +
                        `</option>
        `
                );
            });
        },
        error: function (data) {},
    });
});

$("#searchCustomerByMantagheh").on("change", () => {
    let searchTerm1 = $("#searchCustomerByMantagheh").val();
    $("#mantaghehId").val(searchTerm1);
    $.ajax({
        method: "get",
        url: baseUrl + "/searchCustomerByMantagheh",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm1,
        },
        async: true,
        success: function (msg) {
            // $('.crmDataTable').dataTable().fnDestroy();
            $("#customerListBody1").empty();
            msg.forEach((element, index) => {
                let backgroundColor = "";
                if (element.maxTime) {
                    backgroundColor = "lightblue";
                }
                $("#customerListBody1").append(
                    `
                <tr onclick="selectAndHighlight(this)" style="background-color:` + backgroundColor +`">
                <td>` +(index + 1) +`</td>
                <td style="width:66px;">` + element.PCode +`</td>
                <td>` + element.Name +`</td>
                <td>` +element.peopeladdress + `</td>
                <td>` + element.sabit + `</td>
                <td>` +  element.hamrah +`</td>
                <td>` + element.NameRec + `</td>
                <td>2</td>
                <td style="width:100px;"> <input class="customerList form-check-input" name="customerId" type="radio" value="` + element.PSN +  `_` +  element.GroupCode +`"></td>
                </tr>`
                );
            });
            // $('.crmDataTable').dataTable();
            // $('.crmDataTable').dataTable({
            //     "pagingType": "full_numbers"
            // });
        },
        error: function (data) {},
    });
});

$("#searchMantagheh").on("change", function () {
    $.ajax({
        method: "get",
        url: baseUrl + "/searchCustomerByRegion",
        data: {
            _token: "{{ csrf_token() }}",
            rsn: $("#searchMantagheh").val(),
        },
        async: true,
        success: function (arrayed_result) {
            $("#allCustomer").empty();
            arrayed_result.forEach((element, index) => {
                $("#allCustomer").append(
                    `
                <tr onclick="checkCheckBox(this,event)">
                    <td >` +
                        (index + 1) +
                        `</td>
                    <td>` +
                        element.PCode +
                        `</td>
                    <td>` +
                        element.Name +
                        `</td>
                    <td >
                        <input class="form-check-input" name="customerIDs[]" type="checkbox" value="` +
                        element.PSN +
                        `" id="customerId">
                    </td>
                </tr>
            `
                );
            });
        },
        error: function (data) {},
    });
});

$("#searchAddedMantagheh").on("change", function () {
    $.ajax({
        method: "get",
        url: baseUrl + "/searchAddedCustomerByRegion",
        data: {
            _token: "{{ csrf_token() }}",
            rsn: $("#searchAddedMantagheh").val(),
            asn: $("#takhsisToAdminBtn").val(),
        },
        async: true,
        success: function (arrayed_result) {
            $("#addedCustomer").empty();
            arrayed_result.forEach((element, index) => {
                $("#addedCustomer").append(
                    `
            <tr onclick="checkCheckBox(this,event)">
                <td id="radif">` +
                        (index + 1) +
                        `</td>
                <td id="mCode">` +
                        element.PCode +
                        `</td>
                <td>` +
                        element.Name +
                        `</td>
                <td>
                    <input class="form-check-input" name="addedCustomerIDs[]" type="checkbox" value="` +
                        element.PSN +
                        `" id="kalaId">
                </td>
            </tr>
        `
                );
            });
        },
        error: function (data) {},
    });
});

$("#searchAddedNameByMNM").on("keyup", () => {
    $.ajax({
        method: "get",
        url: baseUrl + "/searchAddedCustomerByNameMNM",
        data: {
            _token: "{{ csrf_token() }}",
            rsn: $("#searchAddedMantagheh").val(),
            asn: $("#takhsisToAdminBtn").val(),
            name: $("#searchAddedNameByMNM").val(),
        },
        async: true,
        success: function (arrayed_result) {
            $("#addedCustomer").empty();
            arrayed_result.forEach((element, index) => {
                $("#addedCustomer").append(
                    `
        <tr onclick="checkCheckBox(this,event)">
            <td id="radif">` +
                        (index + 1) +
                        `</td>
            <td id="mCode">` +
                        element.PCode +
                        `</td>
            <td>` +
                        element.Name +
                        `</td>
            <td>
                <input class="form-check-input" name="addedCustomerIDs[]" type="checkbox" value="` +
                        element.PSN +
                        `" id="kalaId">
            </td>
        </tr>
        `
                );
            });
        },
        error: function (data) {},
    });
});

$("#searchNameByMNM").on("keyup", () => {
    $.ajax({
        method: "get",
        url: baseUrl + "/searchCustomerByNameMNM",
        data: {
            _token: "{{ csrf_token() }}",
            rsn: $("#searchMantagheh").val(),
            name: $("#searchNameByMNM").val(),
        },
        async: true,
        success: function (arrayed_result) {
            $("#allCustomer").empty();
            arrayed_result.forEach((element, index) => {
                $("#allCustomer").append(
                    `
        <tr  onclick="checkCheckBox(this,event)">
            <td >` +
                        (index + 1) +
                        `</td>
            <td>` +
                        element.PCode +
                        `</td>
            <td>` +
                        element.Name +
                        `</td>
            <td>
            <input class="form-check-input" name="customerIDs[]" type="checkbox" value="` +
                        element.PSN +
                        `" id="customerId">
            </td>
        </tr>
    `
                );
            });
        },
        error: function (data) {},
    });
});

$("#addCommentForm").submit(function (e) {
    $("#addComment").modal("hide");
    $.ajax({
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (data) {
            swal({
                title: "موفق!",
                text: "ثبت شد!",
                icon: "success",
                buttons: true,
            });
            $("#firstComment").val("");
            $("#secondComment").val("");
            $("#commentDate2").val("");
            moment.locale("en");
            // $('.crmDataTable').dataTable().fnDestroy();
            $("#customerComments").empty();
            data[0].forEach((element, index) => {
                $("#customerComments").append(
                    `<tr class="tbodyTr">
                <td> ` +(index + 1) +` </td>
                <td>` +  moment(element.TimeStamp, "YYYY/M/D HH:mm:ss") .locale("fa") .format("YYYY/M/D") +`</td>
                <td onclick="viewComment(` + element.id + `)">` +element.newComment.substr(0, 10) +`... <i class="fas fa-comment-dots float-end"></i> </td>
                <td onclick="viewNextComment(` +   element.id +  `)">` + element.nexComment.substr(0, 10) +`... <i class="fas fa-comment-dots float-end"></i>  </td>
                <td style="width:111px;">` + moment(element.specifiedDate, "YYYY/M/D HH:mm:ss") .locale("fa").format("YYYY/M/D") +`</td>
                </tr>`
                );
            });
            $("#customerListBody1").empty();
            data[1].forEach((element, index) => {
                let backgroundColor = "";
                if (element.maxTime) {
                    backgroundColor = "lightblue";
                }
                $("#customerListBody1").append(
                    `
            <tr onclick="selectAndHighlight(this)" style="background-color:` + backgroundColor + `">
            <td>` + (index + 1) + `</td>
            <td style="66px;">` + element.PCode + `</td>
            <td>` + element.Name + `</td>
            <td>` +element.peopeladdress +`</td>
            <td>` + element.sabit +`</td>
            <td>` + element.hamrah +`</td>
            <td>` + element.NameRec +`</td>
            <td>2</td>
            <td style="width:100px;"> <input class="customerList form-check-input" name="customerId" type="radio" value="` + element.PSN + `_` + element.GroupCode +`"></td>
            </tr>`
                );
            });
            // $('.crmDataTable').dataTable();
        },
    });
    e.preventDefault();
});
$("#openAddCommentModal").on("click", () => {
    if (!$(".modal.in").length) {
        $(".modal-dialog").css({
            top: 0,
            left: 0,
        });
    }
    $("#addComment").modal({
        backdrop: false,
        show: true,
    });

    $(".modal-dialog").draggable({
        handle: ".modal-header",
    });

    $("#addComment").modal("show");
});
$("#openDashboardForAlarm").on("click", () => {
    let csn = $("#customerSn").val();
    $("#customerSnLogin").val(csn);
    $.ajax({
        method: "get",
        url: baseUrl + "/customerDashboard",
        dataType: "json",
        contentType: "json",
        data: {
            _token: "{{ csrf_token() }}",
            csn: csn,
        },
        async: true,
        success: function (msg) {
            moment.locale("en");
            let exactCustomer = msg[0];
            let factors = msg[1];
            let goodDetails = msg[2];
            let basketOrders = msg[3];
            let comments = msg[4];
            let specialComments = msg[5];
            let specialComment = specialComments[0];
            let assesments = msg[6];
            let returnedFactors = msg[7];
            let loginInfo = msg[8];
            if (specialComment) {
                $("#customerProperty").val(specialComment.comment.trim());
            }
            $("#dashboardTitle").text(exactCustomer.Name);
            $("#customerCode").val(exactCustomer.PCode);
            $("#customerName").val(exactCustomer.Name);
            $("#customerAddress").val(exactCustomer.peopeladdress);
            $("#username").val(exactCustomer.userName);
            $("#password").val(exactCustomer.customerPss);
            $("#mobile1").val(exactCustomer.PhoneStr);
            $("#customerIdForComment").val(exactCustomer.PSN);
            $("#countFactor").val(exactCustomer.countFactor);
            $("#factorTable").empty();
            factors.forEach((element, index) => {
                $("#factorTable").append(
                    `<tr class="tbodyTr">
                <td>` +
                        (index + 1) +
                        `</td>
                <td>` +
                        element.FactDate +
                        `</td>
                <td>نامعلوم</td>
                <td>` +
                        parseInt(element.TotalPriceHDS / 10).toLocaleString(
                            "en-us"
                        ) +
                        `</td>
                <td onclick="showFactorDetails(this)"><input name="factorId" style="display:none"  type="radio" value="` +
                        element.SerialNoHDS +
                        `" /><i class="fa fa-eye" /></td>
            </tr>`
                );
            });

            $("#returnedFactorsBody").empty();
            returnedFactors.forEach((element, index) => {
                $("#returnedFactorsBody").append(
                    `<tr class="tbodyTr">
            <td>` +
                        (index + 1) +
                        `</td>
            <td>` +
                        element.FactDate +
                        `</td>
            <td>نامعلوم</td>
            <td>` +
                        parseInt(element.TotalPriceHDS / 10).toLocaleString(
                            "en-us"
                        ) +
                        `</td>
            <td></td>
            </tr>`
                );
            });
            $("#goodDetail").empty();
            goodDetails.forEach((element, index) => {
                $("#goodDetail").append(
                    `
            <tr class="tbodyTr">
                <td>` +
                        (index + 1) +
                        ` </td>
                <td>` +
                        moment(element.maxTime, "YYYY/M/D HH:mm:ss")
                            .locale("fa")
                            .format("YYYY/M/D") +
                        `</td>
                <td>` +
                        element.GoodName +
                        `</td>
                <td>  </td>
                <td>  </td>
                
            </tr>`
                );
            });

            $("#basketOrders").empty();
            basketOrders.forEach((element, index) => {
                $("#basketOrders").append(
                    `<tr>
                <td>` +
                        (index + 1) +
                        `</td>
                <td>` +
                        moment(element.TimeStamp, "YYYY/M/D HH:mm:ss")
                            .locale("fa")
                            .format("YYYY/M/D") +
                        `</td>
                <td>` +
                        element.GoodName +
                        `</td>
                <td>` +
                        element.Amount +
                        `</td>
                <td>` +
                        element.Fi +
                        `</td>
                </tr>`
                );
            });

            $("#customerLoginInfoBody").empty();
            if (loginInfo) {
                loginInfo.forEach((element, index) => {
                    $("#customerLoginInfoBody").append(
                        `<tr>
                <td>` +(index + 1) +`</td>
                <td>` +moment(element.visitDate, "YYYY/M/D HH:mm:ss").locale("fa").format("YYYY/M/D") + `</td>
                <td>` + element.platform + `</td>
                <td style="width:100px;">` +element.browser +`</td>
                </tr>`
                    );
                });
            }

            $("#customerComments").empty();
            comments.forEach((element, index) => {
                $("#customerComments").append(  `<tr class="tbodyTr">
                <td> ` + (index + 1) + ` </td>
                <td>` + moment(element.TimeStamp, "YYYY/M/D HH:mm:ss")  .locale("fa") .format("YYYY/M/D") +  `</td>
                <td onclick="viewComment(` + element.id +   `)"</td>` +  element.newComment.substr(0, 10) +  `... <i class="fas fa-comment-dots float-end"></i> </td>
                <td onclick="viewNextComment(` +  element.id +  `)">` + element.nexComment.substr(0, 10) + `... <i class="fas fa-comment-dots float-end"></i>  </td>
                <td style=""width:111px;">` + moment(element.specifiedDate, "YYYY/M/D").locale("fa").format("YYYY/M/D") + `</td>
                </tr>`
                );
            });
            $("#customerAssesments").empty();
            assesments.forEach((element, index) => {
                let driverBehavior = "";
                let shipmentProblem = "بله";
                if (element.shipmentProblem == 1) {
                    shipmentProblem = "خیر";
                }
                switch (parseInt(element.driverBehavior)) {
                    case 1:
                        driverBehavior = "عالی";
                        break;
                    case 2:
                        driverBehavior = "خوب";
                        break;
                    case 3:
                        driverBehavior = "متوسط";
                        break;
                    case 4:
                        driverBehavior = "بد";
                        break;
                    default:
                        break;
                }
                $("#customerAssesments").append(
                    `
            <tr>
            <td>` +
                        (index + 1) +
                        `</td>
            <td>` +
                        moment(element.TimeStamp, "YYYY/M/D")
                            .locale("fa")
                            .format("YYYY/M/D") +
                        `</td>
            <td>` +
                        element.comment +
                        `</td>
            <td>` +
                        driverBehavior +
                        `</td>
            <td class="scrollTd">` +
                        shipmentProblem +
                        `</td>
            <td></td>
        </tr>`
                );
            });

            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#customerDashboard").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });
            $("#customerDashboard").modal("show");
        },
        error: function (data) {},
    });
});

function alarmHistory() {
    let factorId = $("#factorAlarm").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/getAlarmHistory",
        data: {
            _token: "{{ csrf_token() }}",
            fsn: factorId,
        },
        async: true,
        success: function (data) {
            $("#alarmHistoryBody").empty();
            data.forEach((element, index) => {
                $("#alarmHistoryBody").append(
                    `
            <tr>
            <td>` +
                        (index + 1) +
                        `</td>
            <td>` +
                        element.alarmDate +
                        `</td>
            <td>` +
                        element.comment +
                        `</td>
           <td></td>
            </tr>`
                );
            });
            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#alarmHistoryModal").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });
            $("#alarmHistoryModal").modal("show");
        },
    });
}
$(".select-highlight tr").click(function () {
    $(this).children("td").children("input").prop("checked", true);
    $(".enableBtn").prop("disabled", false);
    if ($(".enableBtn").is(":disabled")) {
    } else {
        $(".enableBtn").css("color", "red !important");
    }
    $(".select-highlight tr").removeClass("selected");

    $(this).toggleClass("selected");
    $("#customerSn").val(
        $(this).children("td").children("input").val().split("_")[0]
    );
});

function selectAndHighlight(element) {
    $(element).children("td").children("input").prop("checked", true);
    $(".enableBtn").prop("disabled", false);
    if ($(".enableBtn").is(":disabled")) {
    } else {
        $(".enableBtn").css("color", "red !important");
    }
    $(".select-highlight tr").removeClass("selected");

    $(element).toggleClass("selected");
    $("#customerSn").val(
        $(element).children("td").children("input").val().split("_")[0]
    );
}

$("#openCustomerActionModal").on("click", () => {
    let csn = $("#customerSn").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/customerDashboardForAdmin",
        data: {
            _token: "{{csrf_token()}}",
            csn: csn,
        },
        async: true,
        success: function (msg) {
            moment.locale("en");
            let exactCustomer = msg[0];
            let factors = msg[1];
            let goodDetails = msg[2];
            let basketOrders = msg[3];
            let comments = msg[4];
            let specialComments = msg[5];
            let assesments = msg[6];
            let returendFactors = msg[7];
            let specialComment = specialComments[0];
            $("#customerProperty").text(specialComment.comment.trim());
            $("#customerCode").val(exactCustomer.PCode);
            $("#customerName").val(exactCustomer.Name);
            $("#customerAddress").val(exactCustomer.peopeladdress);
            $("#mobile1").val(exactCustomer.PhoneStr);
            $("#username").val(exactCustomer.userName);
            $("#password").val(exactCustomer.customerPss);
            let adminName =
                exactCustomer.adminName.trim() +
                " " +
                exactCustomer.lastName.trim();
            $("#admin").val(adminName);
            $("#customerIdForComment").val(exactCustomer.PSN);
            $("#countFactor").val(exactCustomer.countFactor);
            $("#factorTable").empty();
            factors.forEach((element, index) => {
                $("#factorTable").append(
                    `
            <tr>
                <td>` +
                        (index + 1) +
                        `</td>
                <td>` +
                        element.FactDate +
                        `</td>
                <td>نامعلوم</td>
                <td>` +
                        parseInt(element.TotalPriceHDS / 10).toLocaleString(
                            "en-us"
                        ) +
                        `</td>
                <td onclick="showFactorDetails(this)"><input name="factorId" style="display:none"  type="radio" value="` +
                        element.SerialNoHDS +
                        `" /><i class="fa fa-eye" /></td>
            </tr>
            `
                );
            });

            $("#returnedFactorTable").empty();
            returendFactors.forEach((element, index) => {
                $("#returnedFactorTable").append(
                    `
            <tr>
                <td>` +
                        (index + 1) +
                        `</td>
                <td>` +
                        element.FactDate +
                        `</td>
                <td>نامعلوم</td>
                <td>` +
                        parseInt(element.TotalPriceHDS / 10).toLocaleString(
                            "en-us"
                        ) +
                        `</td>
            </tr>
            `
                );
            });
            $("#goodDetail").empty();
            goodDetails.forEach((element, index) => {
                $("#goodDetail").append(
                    `<tr>
            <td>` +
                        (index + 1) +
                        ` </td>
            <td>` +
                        moment(element.maxTime, "YYYY/M/D")
                            .locale("fa")
                            .format("YYYY/M/D") +
                        `</td>
            <td>` +
                        element.GoodName +
                        `</td>
            <td> </td>
            </tr>`
                );
            });
            $("#basketOrders").empty();
            basketOrders.forEach((element, index) => {
                $("#basketOrders").append(
                    `<tr>
                <td>` +
                        (index + 1) +
                        `</td>
                <td>` +
                        moment(element.TimeStamp, "YYYY/M/D")
                            .locale("fa")
                            .format("YYYY/M/D") +
                        `</td>
                <td>` +
                        element.GoodName +
                        `</td>
                <td>` +
                        element.Amount +
                        `</td>
                <td>` +
                        element.Fi +
                        `</td>
                </tr>`
                );
            });
            $("#customerComments").empty();
            comments.forEach((element, index) => {
                $("#customerComments").append(
                    `<tr>
                <td> ` + (index + 1) + ` </td>
                <td>` + moment(element.TimeStamp, "YYYY/M/D").locale("fa").format("YYYY/M/D") + `</td>
                <td  onclick="viewComment(` +element.id + `)"</td>` +  element.newComment.substr(0, 10) + `... <i class="fas fa-comment-dots float-end"></i> </td>
                <td  onclick="viewNextComment(` + element.id + `)">` + element.nexComment.substr(0, 10) + `... <i class="fas fa-comment-dots float-end"></i>  </td>
                <td style="width:111px !important;">` + moment(element.specifiedDate, "YYYY/M/D").locale("fa").format("YYYY/M/D") +`</td>
                </tr>`
                );
            });
            $("#karbarActionAssesment").empty();
            assesments.forEach((element, index) => {
                let driverBehavior = "";
                let shipmentProblem = "بله";
                if (element.shipmentProblem == 1) {
                    shipmentProblem = "خیر";
                }
                switch (parseInt(element.driverBehavior)) {
                    case 1:
                        driverBehavior = "عالی";
                        break;
                    case 2:
                        driverBehavior = "خوب";
                        break;
                    case 3:
                        driverBehavior = "متوسط";
                        break;
                    case 4:
                        driverBehavior = "بد";
                        break;
                    default:
                        break;
                }
                $("#karbarActionAssesment").append(
                    `
            <tr>
            <td>` +
                        (index + 1) +
                        `</td>
            <td>` +
                        moment(element.TimeStamp, "YYYY/M/D")
                            .locale("fa")
                            .format("YYYY/M/D") +
                        `</td>
            <td>` +
                        element.comment +
                        `</td>
            <td>` +
                        driverBehavior +
                        `</td>
            <td>` +
                        shipmentProblem +
                        `</td>
            <td> <i class="fa fa-eye"/> </td>
            <td><input type="radio" class="form-input"/></td>
        </tr>
            `
                );
            });

            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#reportCustomerModal").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });
            $("#reportCustomerModal").modal("show");
        },
        error: function (data) {},
    });
});

$("#searchAlarmByMantagheh").on("change",function(){
    searchTerm=$("#searchAlarmName").val();
    snMantagheh=$("#searchAlarmByMantagheh").val();
    if(!$("#customerWithOutAlarm").is(":checked")){
    $.ajax({
        method: "get",
        url: baseUrl + "/searchAlarmByMantagheh",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm:searchTerm,
            snMantagheh:snMantagheh
        },
        async: true,
        success: function (msg) {
            $("#alarmsbody").empty();
            msg.forEach((element,index)=>{
                $("#alarmsbody").append(`<tr onClick="setAlarmCustomerStuff(this,`+element.id+`)">
                                            <td >`+(index+1)+`</td>
                                            <td style="width:111px">` +
                                            moment(element.TimeStamp, "YYYY/M/D HH:mm:ss")
                                                .locale("fa")
                                                .format("YYYY/M/D") +`</td>
                                            <td>`+element.Name+`</td>
                                            <td>`+element.PhoneStr+`</td>
                                            <td style="width:99px">`+element.countCycle+`</td>
                                            <td style="width:77px">`+element.NameRec+`</td>
                                            <td style="width:66px">`+element.assignedDays+`</td>
                                            <td >`+element.FactDate+`</td>
                                            <td style="width:111px; color:red">`+element.alarmDate+`</td>
                                            <td style="width:166px">`+element.poshtibanName+` `+element.poshtibanLastName+`</td>
                                            <td><input class="customerList form-check-input" name="customerId" type="radio" value="`+element.PSN+`_`+element.adminSn+`_`+element.SerialNoHDS+`"></td>
                                        </tr>`);
            })
        }
        ,error:function(error){

        }
    });
}else{
    $.ajax({
        method: "get",
        url: baseUrl + "/searchUnAlarmByMantagheh",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm:searchTerm,
            snMantagheh:snMantagheh
        },
        async: true,
        success: function (msg) {
            $("#unalarmsbody").empty();
            msg.forEach((element,index)=>{
                $("#unalarmsbody").append(` <tr  onclick="setUnAlarmStuff(this,`+element.PSN+`,`+element.adminId+`)">
                                                <td >`+(index+1)+`</td>
                                                <td>`+element.Name+`</td>
                                                <td>`+element.PCode+`</td>
                                                <td>`+element.PhoneStr+`</td>
                                                <td style="width:77px">`+element.NameRec+`</td>
                                                <td style="width:111px;">`+element.FactDate+`</td>
                                                <td style="width:166px">`+element.adminName+`</td>
                                                <td><input class="customerList form-check-input" name="customerId" type="radio" value="`+element.PSN+`_`+element.adminId+`_`+element.SerialNoHDS+`"></td>
                                            </tr>`);
            });
        },
        error:function(error){

        }
    });
}
});

function moveCustomerToAdmin(){
    let csn = $("#customerSn").val();
    let firstAdminID = $("#adminSn").val();
    let newAdminSn=$("#adminID").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/takhsisCustomer",
        data: {
            _token: "{{ csrf_token() }}",
            csn: csn,
            asn: newAdminSn,
            FirstAdminID: firstAdminID,
        },
        async: true,
        success: function (msg) {
            if($("#changeAdminModal")){
                $("#changeAdminModal").modal("hide");
            }
            if($("#takhsisCustomerModal")){
                $("#takhsisCustomerModal").modal("hide");
            }
            if(!$("#customerWithOutAlarm").is(":checked")){
            $.ajax({
                method: "get",
                url: baseUrl + "/getAlarms",
                data: {
                    _token: "{{ csrf_token() }}"},
                async: true,
                success: function (msg) {
                    $("#alarmsbody").empty();
                    msg.forEach((element,index)=>{
                        $("#alarmsbody").append(`<tr onClick="setAlarmCustomerStuff(this,`+element.id+`)">
                                                    <td >`+(index+1)+`</td>
                                                    <td  style="width:111px">` +
                                                    moment(element.TimeStamp, "YYYY/M/D HH:mm:ss")
                                                        .locale("fa")
                                                        .format("YYYY/M/D") +`</td>
                                                    <td>`+element.Name+`</td>
                                                    <td>`+element.PhoneStr+`</td>
                                                    <td style="width:99px">`+element.countCycle+`</td>
                                                    <td style="width:77px">`+element.NameRec+`</td>
                                                    <td style="width:66px">`+element.assignedDays+`</td>
                                                    <td >`+element.FactDate+`</td>
                                                    <td style="width:111px; color:red">`+element.alarmDate+`</td>
                                                    <td style="width:166px">`+element.poshtibanName+` `+element.poshtibanLastName+`</td>
                                                    <td><input class="customerList form-check-input" name="customerId" type="radio" value="`+element.PSN+`_`+element.adminSn+`_`+element.SerialNoHDS+`"></td>
                                                </tr>`);
                    })
                }
                ,error:function(error){
        
                }
            });
        }else{
            $.ajax({
                method: "get",
                url: baseUrl + "/getUnAlarmHistory",
                data: {
                    _token: "{{ csrf_token() }}",
                    history:"ALLUNALARMS"
                },
                async: true,
                success: function (msg) {
                    $("#unalarmsbody").empty();
                    msg.forEach((element,index)=>{
                        $("#unalarmsbody").append(`<tr onclick="setUnAlarmStuff(this,`+element.PSN+`,`+element.adminId+`)">
                                                        <td >`+(index+1)+`</td>
                                                        <td>`+element.Name+`</td>
                                                        <td>`+element.PCode+`</td>
                                                        <td>`+element.PhoneStr+`</td>
                                                        <td style="width:77px">`+element.NameRec+`</td>
                                                        <td style="width:111px;">`+element.FactDate+`</td>
                                                        <td style="width:166px">`+element.adminName+`</td>
                                                        <td><input class="customerList form-check-input" name="customerId" type="radio" value="`+element.PSN+`_`+element.adminId+`_`+element.SerialNoHDS+`"></td>
                                                    </tr>`);
                    });
                }
                ,error:function(error){
        
                }
            });
        }
        }
        ,error:function(error){

        }
    });
}

function takhsisCustomerAlarm(){
    let csn = $("#customerSn").val();

    $.ajax({
        method: "get",
        url: baseUrl + "/getCustomerAndAdminInfo",
        data: {
            _token: "{{csrf_token()}}",
            csn: csn,
            asn:0
        },
        async: true,
        success: function (msg) {
            $("#customerToTakhsisBody").empty();
            msg[0].forEach((element,index)=>{
                $("#customerToTakhsisBody").append(`
                <tr>
                    <td>`+(index+1)+`</td>
                    <td> `+element.Name+` </td>
                    <td>`+element.PhoneStr+`</td>
                    <td style="display:none"><input type="radio" value="`+element.PSN+`" name="customerToMove"/></td>
                </tr>`);
            })
            $("#selectKarbarToTakhsis").empty();
            msg[1].forEach((element,index)=>{
                adminType = "";
                discription = "توضیحی ندارد";
                if (element.discription != null) {
                    discription = element.discription;
                }
                switch (element.adminType) {
                    case 2:
                        adminType = "پشتیبان";
                        break;
                    case 1:
                        adminType = "ادمین";
                        break;
                    case 3:
                        adminType = "بازاریاب";
                        break;
                }
                $("#selectKarbarToTakhsis").append(`
                <tr onclick="selectKarbarToTakeCustomer(this,`+element.id+`)">
                    <td>`+(index+1)+`</td>
                    <td> `+element.name+` `+element.lastName+` </td>
                    <td> `+adminType+` </td>
                    <td>`+element.discription+`</td>
                    <td><input type="radio" value="`+element.id+`" name="AdminToMove"/></td>
                </tr>`);
            })

        $("#takhsisCustomerModal").modal("show");
            },
        error:function(error){
            alert("در تخصیص مشتری ارور وجود دارد.")
        }
        });
    

}

function changeAdminAlarm(){
    let csn = $("#customerSn").val();
    let asn = $("#adminSn").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/getCustomerAndAdminInfo",
        data: {
            _token: "{{csrf_token()}}",
            csn: csn,
            asn:asn
        },
        async: true,
        success: function (msg) {
            console.log(msg)
            $("#customerToMoveBody").empty();
            msg[0].forEach((element,index)=>{
                $("#customerToMoveBody").append(`
                <tr>
                    <td>`+(index+1)+`</td>
                    <td> `+element.Name+` </td>
                    <td> `+element.name+` `+element.lastName+` </td>
                    <td>`+element.PhoneStr+`</td>
                    <td style="display:none"><input type="radio" value="`+element.PSN+`" name="customerToMove"/></td>
                </tr>`);
            })
            $("#selectKarbarToMove").empty();
            msg[1].forEach((element,index)=>{
                adminType = "پشتیبان";
                discription = "توضیحی ندارد";
                if (element.discription != null) {
                    discription = element.discription;
                }
                switch (element.adminType) {
                    case 2:
                        adminType = "پشتیبان";
                        break;
                    case 1:
                        adminType = "ادمین";
                        break;
                    case 3:
                        adminType = "بازاریاب";
                        break;
                }
                $("#selectKarbarToMove").append(`
                <tr onclick="selectKarbarToTakeCustomer(this,`+element.id+`)">
                    <td>`+(index+1)+`</td>
                    <td> `+element.name+` `+element.lastName+` </td>
                    <td> `+adminType+` </td>
                    <td>`+element.discription+`</td>
                    <td><input type="radio" value="`+element.id+`" name="AdminToMove"/></td>
                </tr>`);
            })

        $("#changeAdminModal").modal("show");
            },
        error:function(error){
            alert("در تخصیص مشتری ارور وجود دارد.")
        }
        });

}

function selectKarbarToTakeCustomer(element,adminID){
    $("tr").removeClass("selected");
    $(element).toggleClass("selected");
    $("#adminID").val(adminID);
}

function changeAlarm() {
    let csn = $("#customerSn").val();
    let asn = $("#adminSn").val();
    $("#adminIdForAlarm").val(asn);
    $("#customerIdForAlarm").val(csn);
    if (!$(".modal.in").length) {
        $(".modal-dialog").css({
            top: 0,
            left: 0,
        });
    }
    $("#changeAlarm").modal({
        backdrop: false,
        show: true,
    });

    $(".modal-dialog").draggable({
        handle: ".modal-header",
    });
    $("#changeAlarm").modal("show");
}
$("#changeAlarmForm").on("submit", function (e) {
    $.ajax({
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (data) {
            $("#changeAlarm").modal("hide");
            $("#alarmsbody").empty();
            data.forEach((element, index) => {
                $("#alarmsbody").append(
                    `<tr onClick="setAlarmCustomerStuff(this)">
            <td>` +
                        (index + 1) +
                        `</td>
                        <td  style="width:111px">` +
                        moment(element.TimeStamp, "YYYY/M/D HH:mm:ss")
                            .locale("fa")
                            .format("YYYY/M/D") +`</td>
            <td>` +
                        element.Name +
                        `</td>
            <td>` +
                        element.peopeladdress +
                        `</td>
            <td>` +
                        element.PhoneStr.trim() +
                        `</td>
            <td>` +
                        element.NameRec +
                        `</td>
            <td>` +
                        element.assignedDays +
                        `</td>
            <td>` +
                        element.PassedDays +
                        `</td>
            <td>` +
                        element.Name +
                        " " +
                        element.lastName +
                        `</td>
            <td><input class="customerList form-check-input" name="customerId" type="radio" value="` +
                        element.PSN +
                        "_" +
                        element.admin_id +
                        "_" +
                        element.SerialNoHDS +
                        `"></td>
        </tr>`
                );
            });
        },
    });
    e.preventDefault();
});

function assesmentStuff(element) {
    let input = $(element).find("input:radio").prop("checked", true);
    $("#customerSn").val(input.val().split("_")[0]);
    $("#factorSn").val(input.val().split("_")[1]);
    $("#customerIdForAssesment").val(input.val().split("_")[0]);
    $("#factorIdForAssesment").val(input.val().split("_")[1]);
    $("#openDashboard").prop("disabled", false);
    $("#openAssessmentModal1").prop("disabled", false);
    $("#customerSnLogin").val($("#customerSn").val());
    $("#fakeLogin").prop("disabled", false);

    $.ajax({
        method: "get",
        url: baseUrl + "/getFactorDetail",
        data: {
            _token: "{{ csrf_token() }}",
            FactorSn: $("#factorSn").val(),
        },
        async: true,
        success: function (msg) {
            $("#factorInfo").css({ display: "block" });
            let factor = msg[0];
            $("#factorDateP").text(factor.FactDate);
            $("#customerNameFactorP").text(factor.Name);
            $("#customerComenterP").text(factor.Name);
            $("#Admin1P").text(factor.lastName);
            $("#customerAddressFactorP").text(factor.peopeladdress);
            $("#customerPhoneFactorP").text(factor.sabit);
            $("#factorSnFactorP").text(factor.FactNo);
            $("#productListP").empty();
            msg.forEach((element, index) => {
                $("#productListP").append(
                    `                                  <tr>
            <td class="driveFactor">` +
                        (index + 1) +
                        `</td>
            <td>` +
                        element.GoodName +
                        ` </td>
            <td class="driveFactor">` +
                        element.Amount / 1 +
                        `</td>
            <td>` +
                        element.UName +
                        `</td>
            <td>` +
                        (element.Fi / 10).toLocaleString("en-us") +
                        `</td>
            <td style="width:111px;">` +
                        (element.goodPrice / 10).toLocaleString("en-us") +
                        `</td>
            </tr>`
                );
            });
        },
        error: function (data) {},
    });
}

function checkExistance(element) {
    userName = element.value;
    $.ajax({
        method: "get",
        url: baseUrl + "/checkUserNameExistance",
        data: {
            _token: "{{ csrf_token() }}",
            username: userName,
        },
        async: true,
        success: function (msg) {
            if (msg > 0) {
                $("#existAlert").text("قبلا موجود است");
            }
        },
        error: function (data) {},
    });
}
$("#emptyKarbarButton").on("click", () => {
    let asn = $("#emptyKarbarButton").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/getAdminForEmpty",
        data: {
            _token: "{{ csrf_token() }}",
            asn: asn,
        },
        async: true,
        success: function (msg) {
            let admin = msg[0];
            let adminType = "";
            if (admin.adminType == 1) {
                adminType = "ادمین";
            } else {
                if (admin.adminType == 2) {
                    adminType = "پشتیبان";
                } else {
                    if (admin.adminType == 3) {
                        adminType = "بازاریاب";
                    } else {
                        if (admin.adminType == 4) {
                            adminType = "راننده";
                        }
                    }
                }
            }

            let discription = "";
            if (admin.discription != null) {
                discription = admin.discription;
            }
            if (
                admin.adminType != 1 &&
                admin.adminType != 4 &&
                admin.emptyState != 1
            ) {
                $("#emptyKarbar").empty();
                $("#emptyKarbar").append(
                    `<tr>
                        <td> </td>
                        <td style="font-size:18px; font-weight:bold">` +  admin.name +  ` ` +  admin.lastName +  `</td>
                        <td style="font-size:18px; font-weight:bold">` +  adminType +  `</td>
                        <td>` +  discription + `</td>
                        <td> </td>
                     </tr>`
                );

                if (!$(".modal.in").length) {
                    $(".modal-dialog").css({
                        top: 0,
                        left: 0,
                    });
                }
                $("#removeKarbar").modal({
                    backdrop: false,
                    show: true,
                });

                $(".modal-dialog").draggable({
                    handle: ".modal-header",
                });

                if (!$(".modal.in").length) {
                    $(".modal-dialog").css({
                        top: 0,
                        left: 0,
                    });
                }
                $("#removeKarbar").modal({
                    backdrop: false,
                    show: true,
                });

                $(".modal-dialog").draggable({
                    handle: ".modal-header",
                });
                $("#removeKarbar").modal("show");
            }
        },
        error: function (data) {},
    });
});

$("#openDashboard").on("click", () => {
    let csn = $("#customerSn").val();
    $("#customerSnLogin").val($("#customerSn").val());
    $("#customerProperty").val("");
    $.ajax({
        method: "get",
        url: baseUrl + "/customerDashboard",
        dataType: "json",
        contentType: "json",
        data: {
            _token: "{{ csrf_token() }}",
            csn: csn,
        },
        async: true,
        success: function (msg) {
            moment.locale("en");
            let exactCustomer = msg[0];
            let factors = msg[1];
            let goodDetails = msg[2];
            let basketOrders = msg[3];
            let comments = msg[4];
            let specialComments = msg[5];
            let specialComment = specialComments[0];
            let assesments = msg[6];
            let returnedFactors = msg[7];
            let loginInfo = msg[8];
            if (specialComment) {
                $("#customerProperty").val(specialComment.comment.trim());
            }
            $("#dashboardTitle").text(exactCustomer.Name);
            $("#customerCode").text(exactCustomer.PCode);
            $("#customerName").text(exactCustomer.Name);
            $("#customerAddress").text(exactCustomer.peopeladdress);
            $("#username").text(exactCustomer.userName);
            $("#password").text(exactCustomer.customerPss);
            $("#mobile1").text(exactCustomer.PhoneStr);
            $("#customerIdForComment").text(exactCustomer.PSN);
            $("#countFactor").text(exactCustomer.countFactor);
            $("#factorTable").empty();
            factors.forEach((element, index) => {
                $("#factorTable").append(
                    `<tr class="tbodyTr">
                <td>` +
                        (index + 1) +
                        `</td>
                <td>` +
                        element.FactDate +
                        `</td>
                <td>نامعلوم</td>
                <td>` +
                        parseInt(element.TotalPriceHDS / 10).toLocaleString(
                            "en-us"
                        ) +
                        `</td>
                <td onclick="showFactorDetails(this)"><input name="factorId" style="display:none"  type="radio" value="` +
                        element.SerialNoHDS +
                        `" /><i class="fa fa-eye" /></td>
            </tr>`
                );
            });

            $("#returnedFactorsBody").empty();
            returnedFactors.forEach((element, index) => {
                $("#returnedFactorsBody").append(
                    `<tr class="tbodyTr">
            <td>` + (index + 1) +  `</td>
            <td>` + element.FactDate + `</td>
            <td>نامعلوم</td>
            <td>` +  parseInt(element.TotalPriceHDS / 10).toLocaleString( "en-us" ) + `</td>
            <td></td>
            </tr>`
                );
            });
            $("#goodDetail").empty();
            goodDetails.forEach((element, index) => {
                $("#goodDetail").append(
                    `
            <tr class="tbodyTr">
                <td>` +(index + 1) + ` </td>
                <td>` +  moment(element.maxTime, "YYYY/M/D HH:mm:ss")
                            .locale("fa")
                            .format("YYYY/M/D") + `</td>
                <td>` + element.GoodName + `</td>
                <td>  </td>
                <td>  </td>
                
            </tr>`
                );
            });

            $("#basketOrders").empty();
            basketOrders.forEach((element, index) => {
                $("#basketOrders").append(
                    `<tr>
                <td>` + (index + 1) + `</td>
                <td>` + moment(element.TimeStamp, "YYYY/M/D HH:mm:ss")
                            .locale("fa")
                            .format("YYYY/M/D") +  `</td>
                <td>` + element.GoodName + `</td>
                <td>` +  element.Amount +  `</td>
                <td>` + element.Fi +  `</td>
                </tr>`
                );
            });

            $("#customerLoginInfoBody").empty();
            if (loginInfo) {
                loginInfo.forEach((element, index) => {
                    $("#customerLoginInfoBody").append(
                        `<tr>
                        <td>` +  (index + 1) + `</td>
                        <td>` +  moment(element.visitDate, "YYYY/M/D HH:mm:ss").locale("fa").format("YYYY/M/D") +`</td>
                        <td>` +  element.platform + `</td>
                        <td style="width:100px;">` + element.browser +  `</td>
                </tr>`
                    );
                });
            }

            $("#customerComments").empty();
            comments.forEach((element, index) => {
                $("#customerComments").append(
                    `<tr class="tbodyTr">
                <td> ` + (index + 1) +` </td>
                <td>` +  moment(element.TimeStamp, "YYYY/M/D HH:mm:ss").locale("fa").format("YYYY/M/D") +`</td>
                <td onclick="viewComment(` +  element.id + `)"</td>` + element.newComment.substr(0, 10) + `... <i class="fas fa-comment-dots float-end"></i> </td>
                <td onclick="viewNextComment(` + element.id +`)">` +element.nexComment.substr(0, 10) +`... <i class="fas fa-comment-dots float-end"></i>  </td>
                <td style="width:101px !important;">` + moment(element.specifiedDate, "YYYY/M/D").locale("fa").format("YYYY/M/D") +`</td>
                </tr>`
                );
            });
            $("#customerAssesments").empty();
            assesments.forEach((element, index) => {
                let driverBehavior = "";
                let shipmentProblem = "بله";
                if (element.shipmentProblem == 1) {
                    shipmentProblem = "خیر";
                }
                switch (parseInt(element.driverBehavior)) {
                    case 1:
                        driverBehavior = "عالی";
                        break;
                    case 2:
                        driverBehavior = "خوب";
                        break;
                    case 3:
                        driverBehavior = "متوسط";
                        break;
                    case 4:
                        driverBehavior = "بد";
                        break;
                    default:
                        break;
                }
                $("#customerAssesments").append(
                    `
            <tr>
            <td>` + (index + 1) + `</td>
            <td>` + moment(element.TimeStamp, "YYYY/M/D").locale("fa").format("YYYY/M/D") +`</td>
            <td>` +  element.comment +  `</td>
            <td>` +  driverBehavior + `</td>
            <td>` + shipmentProblem + `</td>
            <td style="width:100px"></td>
        </tr>`
                );
            });

            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#customerDashboard").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });
            $("#customerDashboard").modal("show");
        },
        error: function (data) {},
    });
});

function openAssesmentStuff() {
    if ($("#assesToday").is(":checked")) {
    }
    $("#assesType").val("TODAY");
    if ($("#assesPast").is(":checked")) {
        $("#assesType").val("PAST");
    }
    if ($("#assesDone").is(":checked")) {
        $("#openAssesmentStuffBtn").prop("disabled", true);
    }

    if (
        !$("#assesToday").is(":checked") &&
        !$("#assesPast").is(":checked") &&
        !$("#assesDone").is(":checked")
    ) {
        $("#assesType").val("TODAY");
    }

    $("#assesmentDashboard").modal("show");
    $.ajax({
        method: "get",
        url: baseUrl + "/getFactorDetail",
        data: {
            _token: "{{ csrf_token() }}",
            FactorSn: $("#factorSn").val(),
        },
        async: true,
        success: function (msg) {
            let factor = msg[0];
            $("#factorDate").text(factor.FactDate);
            $("#customerNameFactor").text(factor.Name);
            $("#customerComenter").text(factor.Name);
            $("#Admin1").text(factor.lastName);
            $("#customerAddressFactor").text(factor.peopeladdress);
            $("#customerPhoneFactor").text(factor.sabit);
            $("#factorSnFactor").text(factor.FactNo);
            $("#assesmentDashboard").modal("show");
            $("#productList").empty();
            msg.forEach((element, index) => {
                $("#productList").append(
                    `                                  <tr>
            <td class="driveFactor">` +
                        (index + 1) +
                        `</td>
            <td>` +
                        element.GoodName +
                        ` </td>
            <td class="driveFactor">` +
                        element.Amount / 1 +
                        `</td>
            <td>` +
                        element.UName +
                        `</td>
            <td>` +
                        (element.Fi / 10).toLocaleString("en-us") +
                        `</td>
            <td style="width:111px;">` +
                        (element.goodPrice / 10).toLocaleString("en-us") +
                        `</td>
            </tr>`
                );
            });
        },
        error: function (data) {},
    });
}

function showDoneCommentDetail(element) {
    let input = $(element).find("input:radio").prop("checked", true);
    $("#customerSn").val(input.val().split("_")[0]);
    $("#factorSn").val(input.val().split("_")[1]);
    $("#customerIdForAssesment").val(input.val().split("_")[0]);
    $("#factorIdForAssesment").val(input.val().split("_")[1]);
    $("#openAssesmentModal").prop("disabled", false);
    $("#openDashboard").prop("disabled", false);
    $.ajax({
        method: "get",
        url: baseUrl + "/getDonCommentInfo",
        data: {
            _token: "{{@csrf}}",
            factorSn: $("#factorSn").val(),
        },
        async: true,
        success: function (response) {
            $("#doneCommentDate").text(response[0].TimeStamp);
            $("#doneCommentComment").text(response[0].assessComment);
            $("#doneCommentAlarm").text(response[0].alarmDate);
        },
        error: function (error) {
            alert("bad");
        },
    });
}
function getDonComment(history) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getDoneAsses",
        async: true,
        data: {
            _token: "{{@csrf}}",
            history: history,
        },
        success: function (response) {
            console.log(response);
            $("#customerListBodyDone").empty();
            response.forEach((element, index) => {
                $("#customerListBodyDone").append(
                    `
                <tr  onclick="showDoneCommentDetail(this)">
                    <td>` +
                        (index + 1) +
                        `</td>
                    <td>` +
                        element.Name +
                        `</td>
                    <td>` +
                        element.PhoneStr +
                        `</td>
                    <td>` +
                        moment(element.TimeStamp, "YYYY/M/D HH:mm:ss")
                            .locale("fa")
                            .format("HH:mm:ss YYYY/M/D") +
                        `</td>
                    <td>` +
                        element.AdminName +
                        ` ` +
                        element.lastName +
                        `</td>
                    <td> <input class="customerList form-check-input" name="factorId" type="radio" value="` +
                        element.PSN +
                        `_` +
                        element.SerialNoHDS +
                        `"></td>
                </tr>`
                );
            });
        },
        error: function () {
            alert("error occored");
        },
    });
}

$("#inactiveButton").on("click", () => {
    $("#inactiveId").val($("#customerSn").val());

    if (!$(".modal.in").length) {
        $(".modal-dialog").css({
            top: 0,
            left: 0,
        });
    }
    $("#inactiveCustomer").modal({
        backdrop: false,
        show: true,
    });

    $(".modal-dialog").draggable({
        handle: ".modal-header",
    });
    $("#inactiveCustomer").modal("show");
});

$("#addAssesment").submit(function (e) {
    $("#assesmentDashboard").modal("hide");
    $.ajax({
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (data) {
            $("#customersAssesBody").empty();
            data.forEach((element, index) => {
                $("#customersAssesBody").append(
                    `
            <tr onclick="assesmentStuff(this)">
            <td class="no-sort" style="width:40px">` +
                        (index + 1) +
                        `</td>
            <td>` +
                        element.Name +
                        `</td>
            <td>` +
                        (element.TotalPriceHDS / 10).toLocaleString("en") +
                        `</td>
            <td>` +
                        element.FactDate +
                        `</td>
            <td style="width:70px">` +
                        element.FactNo +
                        `</td>
            <td style="width:40px"> <input class="customerList form-check-input" name="factorId" type="radio" value="` +
                        element.PSN +
                        `_` +
                        element.SerialNoHDS +
                        `"></td>
        </tr>
            `
                );
            });
        },
    });
    e.preventDefault();
});

$("#addAssesmentPast").submit(function (e) {
    $("#assesmentDashboard").modal("hide");
    $.ajax({
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (data) {
            $("#customersAssesBody").empty();
            data.forEach((element, index) => {
                $("#customersAssesBody").append(
                    `
            <tr onclick="assesmentStuff(this)">
            <td class="no-sort" style="width:40px">` +
                        (index + 1) +
                        `</td>
            <td>` +
                        element.Name +
                        `</td>
            <td>` +
                        (element.TotalPriceHDS / 10).toLocaleString("en") +
                        `</td>
            <td>` +
                        element.FactDate +
                        `</td>
            <td style="width:40px"> <input class="customerList form-check-input" name="factorId" type="radio" value="` +
                        element.PSN +
                        `_` +
                        element.SerialNoHDS +
                        `"></td>
        </tr>
            `
                );
            });
        },
    });
    e.preventDefault();
});

$("#visitorSearchName").on("keyup", () => {
    let searchTerm = $("#visitorSearchName").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/getCustomerLoginInfo",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm,
        },
        async: true,
        success: function (msg) {
            $("#listVisitorBody").empty();
            msg.forEach((element, index) => {
                $("#listVisitorBody").append(
                    `<tr>
            <td >` +
                        (index + 1) +
                        `</td>
            <td > </td>
            <td >` +
                        moment(element.lastVisit, "YYYY-M-D HH:mm:ss")
                            .locale("fa")
                            .format("D/M/YYYY HH:mm:ss") +
                        `</td>
            <td style="width:244px">` +
                        element.Name +
                        `</td>
            <td >` +
                        element.platform +
                        `</td>
            <td >` +
                        element.browser +
                        `</td>
            <td style="width:77px">` +
                        element.countLogin +
                        `</td>
            <td>` +
                        element.countSameTime +
                        `</td>
            </tr>`
                );
            });
        },
        error: function (data) {
            alert("bad");
        },
    });
});

$("#openCommentTimeTable").on("click", () => {
    $("#addComment").modal("show");
});
$("#addCommentTimeTable").submit(function (e) {
    $("#addComment").modal("hide");
    $.ajax({
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (data) {
            swal({
                title: "موفق!",
                text: "ثبت شد!",
                icon: "success",
                buttons: true,
            });
            $.ajax({
                method: "get",
                url: baseUrl + "/getCustomerForTimeTable",
                data: {
                    _token: "{{ csrf_token() }}",
                    dayDate: $("#dayDate").val(),
                },
                async: true,
                success: function (msg) {
                    if (msg.length > 0) {
                        $("#customerListSection").css({ display: "block" });
                    } else {
                        $("#customerListSection").css({ display: "none" });
                    }
                    // $('.crmDataTable').dataTable().fnDestroy();
                    $("#customerListBody").empty();
                    msg.forEach((element, index) => {
                        $("#customerListBody").append(
                            `
                    <tr  onclick="timeTableCustomerStuff(this)">
                        <td>` + (index + 1) + `</td>
                        <td style="width:66px">` + element.PCode + `</td>
                        <td>` +  element.Name +`</td>
                        <td>` + element.peopeladdress +`</td>
                        <td>` + element.sabit +`</td>
                        <td>` + element.hamrah + `</td>
                        <td>` + element.NameRec +`</td>
                        <td style="width:100px"> <input name="timeTableCustomer" class="form-check-input" type="radio" value="` + element.PSN +  `_` + element.commentId + `"></td>
                    </tr>`
                        );
                    });
                    // $('.crmDataTable').dataTable();
                },
                error: function (data) {},
            });
        },
    });
    e.preventDefault();
    let csn = $("#customerSn").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/customerDashboard",
        data: {
            _token: "{{ csrf_token() }}",
            csn: csn,
        },
        async: true,
        success: function (msg) {
            let exactCustomer = msg[0];
            let factors = msg[1];
            let goodDetails = msg[2];
            let basketOrders = msg[3];
            $("#dashboardTitle").text(exactCustomer.Name);
            $("#customerCode").val(exactCustomer.PCode);
            $("#customerName").val(exactCustomer.Name);
            $("#customerAddress").val(exactCustomer.peopeladdress);
            $("#customerAddress").val(exactCustomer.peopeladdress);
            $("#mobile1").val(exactCustomer.PhoneStr);
            $("#customerIdForComment").val(exactCustomer.PSN);
            $("#countFactor").val(exactCustomer.countFactor);
            $("#factorTable").empty();
            factors.forEach((element, index) => {
                $("#factorTable").append(
                    `
            <tr>
                <td>` +
                        (index + 1) +
                        `</td>
                <td>` +
                        element.FactDate +
                        `</td>
                <td>نامعلوم</td>
                <td>` +
                        parseInt(element.TotalPriceHDS / 10).toLocaleString(
                            "en-us"
                        ) +
                        `</td>
                <td onclick="showFactorDetails(this)"><span><input name="factorId" style="display:none"  type="radio" value="` +
                        element.SerialNoHDS +
                        `" /><i class="fa fa-eye" /></span></td>
            </tr>
            `
                );
            });
            $("#goodDetail").empty();
            goodDetails.forEach((element, index) => {
                $("#goodDetail").append(
                    `
            <tr>
            <td> ` +
                        (index + 1) +
                        ` </td>
            <td>` +
                        element.TimeStamp +
                        `</td>
            <td>` +
                        element.GoodName +
                        `</td>
            <td>` +
                        element.Amount +
                        `</td>
            <td>` +
                        element.Fi +
                        `</td>

            </tr >`
                );
            });
            $("#basketOrders").empty();
            basketOrders.forEach((element, index) => {
                $("#basketOrders").append(
                    `<tr>
                <td>` +
                        (index + 1) +
                        `</td>
                <td>` +
                        element.TimeStamp +
                        `</td>
                <td>` +
                        element.GoodName +
                        `</td>
                <td>` +
                        element.Amount +
                        `</td>
                <td>` +
                        element.Fi +
                        `</td>
                </tr>`
                );
            });

            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#customerDashboard").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });
            $("#customerDashboard").modal("show");
        },
        error: function (data) {},
    });
});

function setAdminStuffForMove(element) {
    $(element).find("input:radio").prop("checked", true);
    let input = $(element).find("input:radio");
    let adminId = input.val();
    $("#adminID").val(adminId);
}

function refreshDashboard() {
    $("#addComment").modal("hide");
    let csn = $("#customerSn").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/customerDashboard",
        data: {
            _token: "{{ csrf_token() }}",
            csn: csn,
        },
        async: true,
        success: function (msg) {
            let exactCustomer = msg[0];
            let factors = msg[1];
            let goodDetails = msg[2];
            let basketOrders = msg[3];
            $("#dashboardTitle").text(exactCustomer.Name);
            $("#customerCode").val(exactCustomer.PCode);
            $("#customerName").val(exactCustomer.Name);
            $("#customerAddress").val(exactCustomer.peopeladdress);
            $("#customerAddress").val(exactCustomer.peopeladdress);
            $("#mobile1").val(exactCustomer.PhoneStr);
            $("#customerIdForComment").val(exactCustomer.PSN);
            $("#countFactor").val(exactCustomer.countFactor);
            $("#factorTable").empty();
            factors.forEach((element, index) => {
                $("#factorTable").append(
                    `
            <tr>
                <td>` +
                        (index + 1) +
                        `</td>
                <td>` +
                        element.FactDate +
                        `</td>
                <td>نامعلوم</td>
                <td>` +
                        parseInt(element.TotalPriceHDS / 10).toLocaleString(
                            "en-us"
                        ) +
                        `</td>
                <td onclick="showFactorDetails(this)"><span><input name="factorId" style="display:none"  type="radio" value="` +
                        element.SerialNoHDS +
                        `" /><i class="fa fa-eye" /></span></td>
            </tr>
            `
                );
            });
            $("#goodDetail").empty();
            goodDetails.forEach((element, index) => {
                $("#goodDetail").append(
                    `
            <tr>
            <td>` +
                        (index + 1) +
                        ` </td>
            <td>` +
                        element.TimeStamp +
                        `</td>
            <td>` +
                        element.GoodName +
                        `</td>
            <td>` +
                        element.Amount +
                        `</td>
            <td>` +
                        element.Fi +
                        `</td>
            </tr >`
                );
            });
            $("#basketOrders").empty();
            basketOrders.forEach((element, index) => {
                $("#basketOrders").append(
                    `<tr>
                <td>` +
                        (index + 1) +
                        `</td>
                <td>` +
                        element.TimeStamp +
                        `</td>
                <td>` +
                        element.GoodName +
                        `</td>
                <td>` +
                        element.Amount +
                        `</td>
                <td>` +
                        element.Fi +
                        `</td>
                </tr>`
                );
            });

            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#customerDashboard").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });
            $("#customerDashboard").modal("show");
        },
        error: function (data) {},
    });
}

function viewComment(id) {
    let comment;
    $.ajax({
        method: "get",
        url: baseUrl + "/getFirstComment",
        data: {
            _token: "{{ csrf_token() }}",
            commentId: id,
        },
        async: true,
        success: function (msg) {
            comment = msg.newComment;
            $("#readCustomerComment1").text(comment);
            $("#viewComment").modal("show");
        },
        error: function (data) {},
    });
}

function viewNextComment(id) {
    let comment;
    $.ajax({
        method: "get",
        url: baseUrl + "/getFirstComment",
        data: {
            _token: "{{ csrf_token() }}",
            commentId: id,
        },
        async: true,
        success: function (msg) {
            comment = msg.nexComment;
            $("#readCustomerComment1").text(comment);

            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#viewComment").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });

            $("#viewComment").modal("show");
        },
        error: function (data) {},
    });
}

$("#viewComment").blur(function () {
    $("#viewComment").modal("hide");
    $("#readCustomerComment1").empty();
});

function showTimeTableTasks(element,adminId) {
    let input = $(element).find("input:radio");
    $("#dayDate").val(input.val());
    $("#openDashboard").prop("disabled",false);
    $("#returnCustomer").prop("disabled",false);
    $.ajax({
        method: "get",
        url: baseUrl + "/getCustomerForTimeTable",
        data: {
            _token: "{{ csrf_token() }}",
            dayDate: input.val(),
            asn:adminId
        },
        async: true,
        success: function (msg) {
            $("#customerListBody").empty();
            msg.forEach((element, index) => {
                $("#customerListBody").append(
                    `
            <tr  onclick="timeTableCustomerStuff(this)">
                <td>` +  (index + 1) + `</td>
                <td style="width:66px">` + element.PCode +  `</td>
                <td>` +  element.Name + `</td>
                <td>` +  element.peopeladdress + `</td>
                <td>` +  element.sabit + `</td>
                <td>` +  element.hamrah +  `</td>
                <td>` + element.NameRec + `</td>
                <td style="width:100px;"> <input name="timeTableCustomer" class="form-check-input" type="radio" value="` + element.PSN + `_` + element.commentId +`"></td>
            </tr>`
                );
            });
            // $('.crmDataTable').dataTable();
            $("#customreForCallModal").modal("show");
        },
        error: function (data) {},
    });
}

function timeTableCustomerStuff(element) {
    let input = $(element).find("input:radio").prop("checked", true);

    $("#customerSn").val(input.val().split("_")[0]);
    $("#commentSn").val(input.val().split("_")[1]);
    $(".enableBtn").prop("disabled", false);
    
}

function showAssesComment(id) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getAssesComment",
        data: {
            _token: "{{ csrf_token() }}",
            assesId: id,
        },
        async: true,
        success: function (msg) {
            $("#assesComment").text(msg.comment);
            $("#readAssesComment").modal("show");
        },
        error: function (data) {

        },
    });
}

function returnedCustomerStuff(element) {
    let input = $(element).find("input:radio").prop("checked", true);
    $("#customerSn").val(input.val().split("_")[0]);
    $("#adminSn").val(input.val().split("_")[1]);
    $(".enableBtn").prop("disabled", false);
    $(".enableBtn").val(input.val().split("_")[0]);
}

$("#returnCustomer").on("click", () => {
    let csn = $("#customerSn").val();
    $("#returnCustomerId").val(csn);
    $("#returnComment").modal("show");
});
$("#cancelSetAlarm").on("click", () => {
    swal({
        title: "اخطار!",
        text: "آیا می خواهید بدون ذخیره خارج شوید؟",
        icon: "warning",
        buttons: true,
    }).then(function (value) {
        if (value === true) {
            $("#changeAlarm").modal("hide");
        } else {
            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#changeAlarm").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });
            $("#changeAlarm").modal("show");
        }
    });
});

$("#cancelEditCustomer").on("click", () => {
    swal({
        title: "اخطار!",
        text: "آیا می خواهید بدون ذخیره خارج شوید؟",
        icon: "warning",
        buttons: true,
    }).then(function (value) {
        if (value === true) {
            $("#editNewCustomer").modal("hide");
        } else {
            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#editNewCustomer").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });

            $("#editNewCustomer").modal("show");
        }
    });
});

$("#cancelinActive").on("click", () => {
    swal({
        title: "اخطار!",
        text: "آیا می خواهید بدون ذخیره خارج شوید؟",
        icon: "warning",
        buttons: true,
    }).then(function (value) {
        if (value === true) {
            $("#inactiveCustomer").modal("hide");
        } else {
            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#inactiveCustomer").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });

            $("#inactiveCustomer").modal("show");
        }
    });
});

$("#cancelTakhsis").on("click", () => {
    swal({
        title: "اخطار!",
        text: "آیا می خواهید بدون ثبت تخصیص خارج شوید؟",
        icon: "warning",
        buttons: true,
    }).then(function (value) {
        if (value === true) {
            $("#takhsesKarbar").modal("hide");
        } else {
            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#takhsesKarbar").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });

            $("#takhsesKarbar").modal("show");
        }
    });
});

$("#cancelCommentButton").on("click", () => {
    swal({
        title: "اخطار!",
        text: "آیا می خواهید بدون ذخیره خارج شوید؟",
        icon: "warning",
        buttons: true,
    }).then(function (value) {
        if (value === true) {
            $("#addComment").modal("hide");
        } else {
            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#addComment").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });

            $("#addComment").modal("show");
        }
    });
});

$("#cancelReturn").on("click", () => {
    swal({
        title: "اخطار!",
        text: "آیا می خواهید بدون ذخیره خارج شوید؟",
        icon: "warning",
        buttons: true,
    }).then(function (value) {
        if (value === true) {
            $("#returnComment").modal("hide");
        } else {
            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#returnComment").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });

            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#returnComment").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });

            $("#returnComment").modal("show");
        }
    });
});

$("#cancelInActive").on("click", () => {
    swal({
        title: "اخطار!",
        text: "آیا می خواهید بدون ذخیره خارج شوید؟",
        icon: "warning",
        buttons: true,
    }).then(function (value) {
        if (value === true) {
            $("#inactiveCustomer").modal("hide");
        } else {
            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#inactiveCustomer").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });

            $("#inactiveCustomer").modal("show");
        }
    });
});

$("#cancelAssesment").on("click", () => {
    swal({
        title: "اخطار!",
        text: "آیا می خواهید بدون ذخیره خارج شوید؟",
        icon: "warning",
        buttons: true,
    }).then(function (value) {
        if (value === true) {
            $("#assesmentDashboard").modal("hide");
        } else {
            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#assesmentDashboard").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });

            $("#assesmentDashboard").modal("show");
        }
    });
});

$("#returnCustomerForm").submit(function (e) {
    $("#returnComment").modal("hide");
    $.ajax({
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (data) {
            swal({
                title: "موفق!",
                text: "ثبت شد!",
                icon: "success",
                buttons: true,
            });
            // $('.crmDataTable').dataTable().fnDestroy();
            $("#customerListBody1").empty();
            data.forEach((element, index) => {
                let backgroundColor = "";
                if (element.countComment > 0) {
                    backgroundColor = "lightblue";
                }
                $("#customerListBody1").append(
                    `
            <tr onclick="selectAndHighlight(this)" style="background-color:` + backgroundColor +`">
                    <td>` + (index + 1) +`</td>
                    <td style="width:66px">` + element.PCode + `</td>
                    <td>` + element.Name + `</td>
                    <td>` + element.peopeladdress + `</td>
                    <td>` + element.sabit +`</td>
                    <td>` +  element.hamrah +`</td>
                    <td>` + element.NameRec + `</td>
                    <td>2</td>
                    <td style="width:100px;"> <input class="customerList form-check-input" name="customerId" type="radio" value="` +  element.PSN +  `_` +  element.GroupCode + `"></td>
            </tr>`
                );
            });
            // $('.crmDataTable').dataTable();
        },
    });
    e.preventDefault();
});

$("#openDashboardAlarm").on("click", () => {
    if (!$(".modal.in").length) {
        $(".modal-dialog").css({
            top: 0,
            left: 0,
        });
    }
    $("#karbarAlarm").modal({
        backdrop: false,
        show: true,
    });

    $(".modal-dialog").draggable({
        handle: ".modal-header",
    });

    if (!$(".modal.in").length) {
        $(".modal-dialog").css({
            top: 0,
            left: 0,
        });
    }
    $("#karbarAlarm").modal({
        backdrop: false,
        show: true,
    });

    $(".modal-dialog").draggable({
        handle: ".modal-header",
    });

    $("#karbarAlarm").modal("show");
});

function takhsisCustomer() {
    $("#takhsesKarbar").modal("hide");
    let csn = $("#customerSn").val();
    let FirstAdminID = $("#adminSn").val();
    let asn = $("input[name='AdminId']:checked").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/takhsisCustomer",
        data: {
            _token: "{{ csrf_token() }}",
            csn: csn,
            asn: asn,
            FirstAdminID: FirstAdminID,
        },
        async: true,
        success: function (msg) {
            // $('.crmDataTable').dataTable().fnDestroy();
            $("#returnedCustomerList").empty();
            msg.forEach((element, index) => {
                $("#returnedCustomerList").append(
                    `
        <tr onclick="returnedCustomerStuff(this)">
        <td>` +
                        (index + 1) +
                        `</td>
        <td>` +
                        element.Name +
                        `</td>
        <td>` +
                        element.PCode +
                        `</td>
        <td>` +
                        element.peopeladdress +
                        `</td>
        <td>` +
                        element.PhoneStr +
                        `</td>
        <td> <input class="customerList form-check-input" name="customerId[]" type="radio" value="` +
                        element.PSN +
                        `_` +
                        element.adminId +
                        `"></td>
    </tr>`
                );
            });
            // $('.crmDataTable').dataTable();
        },
        error: function (data) {},
    });
}
function openEditCustomerModalForm(csn) {
    let customerId = csn;
    $.ajax({
        method: "get",
        url: baseUrl + "/getCustomerInfo",
        data: {
            _token: "{{ csrf_token() }}",
            csn: customerId,
        },
        async: true,
        success: function (respond) {
            let exactCustomerInfo = respond[0];
            let phones = respond[1];
            let cities = respond[2];
            let mantagheh = respond[3];

            $("#customerID").val(exactCustomerInfo.PSN);
            $("#name").val(exactCustomerInfo.Name);
            $("#PCode").val(exactCustomerInfo.PCode);
            $("#mobilePhone").val(phones[0].hamrah);
            $("#sabitPhone").val(phones[0].sabit);
            $("#gender").empty();
            $("#gender").append(`
            <option value="2" >مرد</option>
            <option value="1" >زن</option>`);
            $("#snNahiyehE").empty();
            cities.forEach((element, index) => {
                let selectRec = "";
                if (element.SnMNM == exactCustomerInfo.SnNahiyeh) {
                    selectRec = "selected";
                }
                $("#snNahiyehE").append(
                    `<option value="` +
                        element.SnMNM +
                        `" ` +
                        selectRec +
                        `>` +
                        element.NameRec +
                        `</option>`
                );
            });

            $("#snMantaghehE").empty();
            mantagheh.forEach((element, index) => {
                let selectRec = "";
                if (element.SnMNM == exactCustomerInfo.SnMantagheh) {
                    selectRec = "selected";
                }
                $("#snMantaghehE").append(
                    `<option value="` +
                        element.SnMNM +
                        `" ` +
                        selectRec +
                        `>` +
                        element.NameRec +
                        `</option>`
                );
            });
            $("#peopeladdress").val(exactCustomerInfo.peopeladdress);
            $("#password").val(exactCustomerInfo.customerPss);

            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#editNewCustomer").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });
            $("#editNewCustomer").modal("show");
        },
        error: function (data) {},
    });
}
function takhsisNewCustomer() {
    $("#takhsesKarbar").modal("hide");
    let csn = $("#customerSn").val();
    let asn = $("input[name='AdminId']:checked").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/takhsisNewCustomer",
        data: {
            _token: "{{ csrf_token() }}",
            csn: csn,
            asn: asn,
        },
        async: true,
        success: function (msg) {
            swal("موفقانه اختصاص داده شد.", {
                icon: "success",
            });
            // $('.crmDataTable').dataTable().fnDestroy();
            $("#customerListBody1").empty();
            msg.forEach((element, index) => {
                $("#customerListBody1").append(
                    `
            <tr>
            <td style="width:40px">` +
                        index +
                        1 +
                        `</td>
            <td>` +
                        element.Name +
                        `</td>
            <td>` +
                        element.hamrah +
                        `</td>
            <td>` +
                        element.sabit +
                        `</td>
            <td>` +
                        element.NameRec +
                        `</td>
            <td>` +
                        moment(element.TimeStamp, "YYYY-M-D HH:mm:ss")
                            .locale("fa")
                            .format("HH:mm:ss YYYY/M/D") +
                        `</td>
            <td>` +
                        element.peopeladdress +
                        `</td>
            <td>` +
                        element.adminName +
                        ` ` +
                        element.adminLastName +
                        `</td>
            <td style="width:40px"> <input class="customerList form-check-input" name="customerId" type="radio" value="` +
                        element.PSN +
                        ` ` +
                        element.GroupCode +
                        `"></td>
        </tr>`
                );
            });
            // $('.crmDataTable').dataTable();
        },
        error: function (data) {},
    });
}
// function takhsisNewCustomer() {
//     $("#takhsesKarbar").modal("hide");
//     let csn = $("#customerSn").val();
//     let FirstAdminID = $("#adminSn").val();
//     let asn = $("input[name='AdminId']:checked").val();
//     $.ajax({
//         method: 'get',
//         url: baseUrl + "/takhsisCustomerFromEmpty",
//         data: {
//             _token: "{{ csrf_token() }}",
//             csn: csn,
//             asn: asn,
//             FirstAdminID: FirstAdminID
//         },
//         async: true,
//         success: function(msg) {
//             // $('.crmDataTable').dataTable().fnDestroy();
//             $("#returnedCustomerList").empty();
//             msg.forEach((element, index) => {
//                 $("#returnedCustomerList").append(`
//             <tr onclick="returnedCustomerStuff(this)">
//             <td>` + (index + 1) + `</td>
//             <td>` + element.Name + `</td>
//             <td>` + element.PCode + `</td>
//             <td>` + element.peopeladdress + `</td>
//             <td>` + element.PhoneStr + `</td>
//             <td> <input class="customerList form-check-input" name="customerId[]" type="radio" value="` + element.PSN + `_` + element.adminId + `"></td>
//         </tr>`);
//             });
//             // $('.crmDataTable').dataTable();

//         },
//         error: function(data) {}
//     });
// }

function activateCustomer() {
    let csn = $("#customerSn").val();
    let asn = $("input[name='AdminId']:checked").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/activateCustomer",
        data: {
            _token: "{{ csrf_token() }}",
            csn: csn,
            asn: asn,
        },
        async: true,
        success: function (msg) {
            $("#inactiveCustomerBody").empty();
            msg.forEach((element, index) => {
                $("#inactiveCustomerBody").append(
                    `
        <tr onclick="setInActiveCustomerStuff(this)">
        <td>` +
                        (index + 1) +
                        `</td>
        <td>` +
                        element.Name +
                        `</td>
        <td>` +
                        element.PCode +
                        `</td>
        <td>` +
                        element.peopeladdress +
                        `</td>
        <td>` +
                        element.PhoneStr +
                        `</td>
        <td> <input class="customerList form-check-input" name="customerId[]" type="radio" value="` +
                        element.PSN +
                        `"></td>
    </tr>`
                );
            });
            $("#takhsesKarbar").modal("hide");
        },
        error: function (data) {},
    });
}

$("#takhsisButton").on("click", () => {
    $("#inactiveId").val($("#customerSn").val());

    if (!$(".modal.in").length) {
        $(".modal-dialog").css({
            top: 0,
            left: 0,
        });
    }
    $("#takhsesKarbar").modal({
        backdrop: false,
        show: true,
    });

    $(".modal-dialog").draggable({
        handle: ".modal-header",
    });
    $("#takhsesKarbar").modal("show");
});

function setInActiveCustomerStuff(element) {
    let input = $(element).find("input:radio").prop("checked", true);
    $("#customerSn").val(input.val());
    $(".enableBtn").val(input.val());
    $(".enableBtn").prop("disabled",false);
}

$("#inactiveCustomerForm").submit(function (e) {
    swal({
        title: "مطمئین هستید؟",
        text: "پس از غیر فعالسازی این مشتری به لیست غیر فعالها اضافه می شود. !",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            $("#inactiveCustomer").modal("hide");
            $.ajax({
                url: $(this).attr("action"),
                data: $(this).serialize(),
                success: function (msg) {
                    // $('.crmDataTable').dataTable().fnDestroy();
                    $("#returnedCustomerList").empty();
                    $("#changeAlarm").modal("hide");
                    $("#alarmsbody").empty();
                    msg.forEach((element, index) => {
                        $("#alarmsbody").append(
                            `<tr onClick="setAlarmCustomerStuff(this)">
                    <td>` +
                                (index + 1) +
                                `</td>
                                <td  style="width:111px">` +
                                moment(element.TimeStamp, "YYYY/M/D HH:mm:ss")
                                    .locale("fa")
                                    .format("YYYY/M/D") +`</td>
                    <td>` +
                                element.Name +
                                `</td>
                    <td>` +
                                element.peopeladdress +
                                `</td>
                    <td>` +
                                element.sabit.trim() +
                                ` ` +
                                element.hamrah.trim() +
                                `</td>
                    <td>` +
                                element.NameRec +
                                `</td>
                    <td>` +
                                element.assignedDays +
                                `</td>
                    <td>` +
                                element.PassedDays +
                                `</td>
                    <td>` +
                                element.Name +
                                " " +
                                element.lastName +
                                `</td>
                    <td><input class="customerList form-check-input" name="customerId" type="radio" value="` +
                                element.PSN +
                                "_" +
                                element.admin_id +
                                "_" +
                                element.SerialNoHDS +
                                `"></td>
                </tr>`
                        );
                    });
                    // $('.crmDataTable').dataTable();
                    swal("مشتری غیر فعال شد", {
                        icon: "success",
                    });
                },
            });
        }
    });
    e.preventDefault();
});

function removeStaff(adminId) {
    swal({
        title: "مطمئین هستید؟",
        text: "پس از تخلیه نمی توانید این مشتریان را برگردانید !",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            $("#transferLoader").css("display", "block");
            $.ajax({
                method: "get",
                url: baseUrl + "/emptyAdmin",
                data: {
                    _token: "{{ csrf_token() }}",
                    asn: adminId,
                },
                async: true,
                success: function (msg) {
                    if (msg == 1) {
                        $("#transferLoader").css("display", "none");
                        swal("مشتریان تخلیه گردید", {
                            icon: "success",
                        });
                        $("#addedCustomer").empty();
                    } else {
                    }
                },
                error: function (data) {},
            });
        }
    });
}


function moveStaff() {
    swal({
        title: "مطمئین هستید؟",
        text: "پس از انتقال نمی توانید این مشتریان را برگردانید !",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            $("#moveKarbar").modal("hide");
            $("#transferLoader").css("display", "block");
            $.ajax({
                method: "get",
                url: baseUrl + "/moveCustomerToAdmin",
                data: {
                    _token: "{{ csrf_token() }}",
                    holderID: $("#adminID").val(),
                    giverID: $("#adminTakerId").val(),
                },
                async: true,
                success: function (msg) {
                    if (msg == 1) {
                        $("#transferLoader").css("display", "none");
                        swal("مشتریان انتقال گردید", {
                            icon: "success",
                        });
                    } else {
                    }
                },
                error: function (data) {},
            });
        }
    });
}

$("#moveKarbarButton").on("click", () => {
    let asn = $("#moveKarbarButton").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/getAdminForMove",
        data: {
            _token: "{{ csrf_token() }}",
            asn: asn,
        },
        async: true,
        success: function (msg) {
            let adminArray = msg[0];
            let admin = adminArray[0];
            let otherAdmins = msg[1];
            let adminType = "";
            let discription = "توضیحی ندارد.";
            if (admin.discription != null) {
                discription = admin.discription;
            }
            if (admin.adminType == 1) {
                adminType = "ادمین";
            } else {
                if (admin.adminType == 2) {
                    adminType = "پشتیبان";
                } else {
                    if (admin.adminType == 3) {
                        adminType = "بازاریاب";
                    } else {
                        if (admin.adminType == 4) {
                            adminType = "راننده";
                        }
                    }
                }
            }
            if (
                admin.adminType != 1 &&
                admin.adminType != 4 &&
                admin.emptyState != 1
            ) {
                $("#adminToMove").empty();
                $("#adminToMove").append(
                    `<tr>
                  <td> 1 </td>
                <td style="font-size:18px; font-weight:bold">` +
                        admin.name +
                        ` ` +
                        admin.lastName +
                        `</td>
                <td style="font-size:18px; font-weight:bold">` +
                        adminType +
                        `</td>
                <td>` +
                        discription +
                        `</td>
                        <td>  </td>
                </tr>`
                );

                if (!$(".modal.in").length) {
                    $(".modal-dialog").css({
                        top: 0,
                        left: 0,
                    });
                }
                $("#moveKarbar").modal({
                    backdrop: false,
                    show: true,
                });

                $(".modal-dialog").draggable({
                    handle: ".modal-header",
                });

                $("#moveKarbar").modal("show");
            }
            $("#selectKarbarToMove").empty();
            otherAdmins.forEach((element, index) => {
                adminType = "پشتیبان";
                discription = "توضیحی ندارد";
                if (element.discription != null) {
                    discription = element.discription;
                }
                switch (element.adminType) {
                    case 2:
                        adminType = "پشتیبان";
                        break;
                    case 3:
                        adminType = "بازاریاب";
                        break;
                }
                $("#selectKarbarToMove").append(
                    `
            <tr onclick="setAdminStuffForMove(this)">
            <td>` +
                        (index + 1) +
                        `</td>
            <td>` +
                        element.name +
                        " " +
                        element.lastName +
                        `</td>
            <td>` +
                        adminType +
                        `</td>
            <td>` +
                        discription +
                        `</td>
            <td>
                <input class="form-check-input" name="adminId" type="radio" value="` +
                        element.id +
                        `">
            </td>
        </tr>`
                );
            });
        },
        error: function (data) {},
    });
});

$("#cancelAddAddmin").on("click", () => {
    swal({
        title: "اخطار!",
        text: "می خواهید بدون ذخیره خارج شوید؟",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            $("#newAdmin").modal("hide");
        } else {
            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#newAdmin").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });
            $("#newAdmin").modal("show");
        }
    });
});

$("#cancelEditProfile").on("click", () => {
    swal({
        title: "اخطار!",
        text: "می خواهید بدون ویرایش خارج شوید؟",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            $("#editProfile").modal("hide");
        } else {
            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#editProfile").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });

            $("#editProfile").modal("show");
        }
    });
});

$("#cancelRemoveKarbar").on("click", () => {
    swal({
        title: "اخطار!",
        text: "می خواهید بدون ذخیره خارج شوید؟",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            $("#removeKarbar").modal("hide");
        } else {
            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#removeKarbar").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });
            $("#removeKarbar").modal("show");
        }
    });
});

$("#cancelMoveKarbar").on("click", () => {
    swal({
        title: "اخطار!",
        text: "می خواهید بدون ذخیره خارج شوید؟",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            $("#moveKarbar").modal("hide");
        } else {
            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#moveKarbar").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });
            $("#moveKarbar").modal("show");
        }
    });
});

function setKarbarEditStuff() {
    let asn = $("#editAdmin").val();

    let admyTypes;
    let sexes;
    $("#editAdminID").val(asn);
    $.ajax({
        method: "get",
        url: baseUrl + "/getAdminForMove",
        data: {
            _token: "{{ csrf_token() }}",
            asn: asn,
        },
        async: true,
        success: function (msg) {
            let adminArray = msg[0];
            let admin = adminArray[0];
            let otherAdmins = msg[1];
            let adminType = "";
            let discription = "توضیحی ندارد.";
            let bossAdmins = msg[2];
            $("#adminId").val(admin.id);
            if (
                admin.adminType == 5 ||
                admin.adminType == 4 ||
                admin.adminType == 1
            ) {
                $("#assignBossDiv").css({ display: "none" });
            } else {
                $("#assignBossDiv").css({ display: "block" });
            }

            if (admin.adminType == 2 || admin.adminType == 4) {
                $("#poshtibanDiv").css({ display: "block" });
            } else {
                $("#poshtibanDiv").css({ display: "none" });
            }

            $("#poshtibanType").empty();

            if (admin.poshtibanType == 1) {
                $("#poshtibanType").append(`
            <option selected value="1">پشتیبان حضوری</option>
            <option value="2">پشتیبان هماهنگی</option>
            <option value="3">پشتیبان تلفنی</option>
            <option value="4"> راننده </option>
            <option  value="5">بازاریاب حضوری</option>
            <option value="6">بازاریاب هماهنگی</option>
            <option value="7">بازاریاب تلفنی</option>`);
            }

            if (admin.poshtibanType == 2) {
                $("#poshtibanType").append(`
            <option  value="1">پشتیبان حضوری</option>
            <option selected value="2">پشتیبان هماهنگی</option>
            <option value="3">پشتیبان تلفنی</option>
            <option value="4"> راننده </option>
            <option  value="5">بازاریاب حضوری</option>
            <option value="6">بازاریاب هماهنگی</option>
            <option value="7">بازاریاب تلفنی</option>`);
            }

            if (admin.poshtibanType == 3) {
                $("#poshtibanType").append(`
            <option value="1">پشتیبان حضوری</option>
            <option value="2">پشتیبان هماهنگی</option>
            <option selected value="3">پشتیبان تلفنی</option>
            <option value="4"> راننده </option>
            <option  value="5">بازاریاب حضوری</option>
            <option value="6">بازاریاب هماهنگی</option>
            <option value="7">بازاریاب تلفنی</option>`);
            }
            if (admin.poshtibanType == 0) {
                $("#poshtibanType").append(`
            <option value="1">پشتیبان حضوری</option>
            <option value="2">پشتیبان هماهنگی</option>
            <option value="3">پشتیبان تلفنی</option>
            <option value="4"> راننده </option>
            <option value="5">بازاریاب حضوری</option>
            <option value="6">بازاریاب هماهنگی</option>
            <option value="7">بازاریاب تلفنی</option>`);
            }
            if (admin.poshtibanType == 4) {
                $("#poshtibanType").append(`
            <option selected value="1">پشتیبان حضوری</option>
            <option value="2">پشتیبان هماهنگی</option>
            <option value="3">پشتیبان تلفنی</option>
            <option selected value="4"> راننده </option>
            <option  value="5">بازاریاب حضوری</option>
            <option value="6">بازاریاب هماهنگی</option>
            <option value="7">بازاریاب تلفنی</option>`);
            }

            if (admin.employeeType == 1) {
                $("#managerEdit").prop("selected", true);
                $("#saleLineWork" + admin.SaleLineId).prop("selected", true);
                $("#saleLineDivEdit").css("display", "inline");
                $("#headDivEdit").css("display", "none");
                $("#managerDivEdit").css("display", "none");
                $("#employeeJobDivEdit").css("display", "none");
            }

            if (admin.employeeType == 2) {
                $("#headEdit").prop("selected", true);
                $("#manageWork" + admin.bossId).prop("selected", true);
                $("#managerDivEdit").css("display", "inline");
                $("#saleLineDivEdit").css("display", "none");
                $("#headDivEdit").css("display", "none");
                $("#employeeJobDivEdit").css("display", "none");
            }

            if (admin.employeeType == 3) {
                $("#jobEdit" + admin.poshtibanType).prop("selected", true);
                $("#headWork" + admin.bossId).prop("selected", true);
                $("#employeeEdit").prop("selected", true);
                $("#headDivEdit").css("display", "inline");
                $("#employeeJobDivEdit").css("display", "inline");
                $("#saleLineDivEdit").css("display", "none");
                $("#managerDivEdit").css("display", "none");
            }

            $("#bosses").empty();
            let hasBoss = false;
            bossAdmins.forEach((element, index) => {
                if (admin.bossId != element.id) {
                    $("#bosses").append(
                        `<option value="` +
                            element.id +
                            `">` +
                            element.name +
                            ` ` +
                            element.lastName +
                            `</option>`
                    );
                } else {
                    if (admin.bossId == element.id) {
                        hasBoss = true;
                        $("#bosses").append(
                            `<option selected value="` +
                                element.id +
                                `">` +
                                element.name +
                                ` ` +
                                element.lastName +
                                `</option>`
                        );
                    }
                }
            });
            if (!hasBoss) {
                $("#bosses").append(
                    `<option selected value="0">هیچکیس</option>`
                );
            } else {
                $("#bosses").append(`<option value="0">هیچکیس</option>`);
            }
            if (admin.discription != null) {
                discription = admin.discription;
            }
            if (admin.adminType == 1) {
                admyTypes = [
                    `<option selected value="1">ادمین</option>`,
                    `<option value="2">پشتیبان</option>`,
                    `<option value="3">بازاریاب</option>`,
                    `<option value="4">راننده</option>`,
                ];
            } else {
                if (admin.adminType == 2) {
                    admyTypes = [
                        `<option  value="1">ادمین</option>`,
                        `<option selected value="2">پشتیبان</option>`,
                        `<option value="3">بازاریاب</option>`,
                        `<option value="4">راننده</option>`,
                    ];
                } else {
                    if (admin.adminType == 3) {
                        admyTypes = [
                            `<option  value="1">ادمین</option>`,
                            `<option  value="2">پشتیبان</option>`,
                            `<option  selected value="3">بازاریاب</option>`,
                            `<option value="4">راننده</option>`,
                        ];
                    } else {
                        if (admin.adminType == 4) {
                            admyTypes = [
                                `<option  value="1">ادمین</option>`,
                                `<option  value="2">پشتیبان</option>`,
                                `<option  value="3">بازاریاب</option>`,
                                `<option selected value="4">راننده</option>`,
                            ];
                        } else {
                            admyTypes = [
                                `<option  value="1">ادمین</option>`,
                                `<option  value="2">پشتیبان</option>`,
                                `<option  value="3">بازاریاب</option>`,
                                `<option value="4">راننده</option>`,
                                `<option selected value="5">سوپر ادمین</option>`,
                            ];
                        }
                    }
                }
            }

            if (admin.sex == 1) {
                sexes = [
                    `<option selected value="1">مرد</option>`,
                    `<option value="2">زن</option>`,
                ];
            } else {
                if (admin.sex == 2) {
                    sexes = [
                        `<option value="1">مرد</option>`,
                        `<option selected value="2">زن</option>`,
                    ];
                } else {
                    sexes = [
                        `<option value="1">مرد</option>`,
                        `<option value="2">زن</option>`,
                    ];
                }
            }

            if (admin.hasAsses == "on") {
                $("#adminHasAssess").prop("checked", true);
            } else {
                $("#adminHasAssess").prop("checked", false);
            }
            if (admin.hasAllCustomer == "on") {
                $("#hasAllCustomer").prop("checked", true);
            } else {
                $("#hasAllCustomer").prop("checked", false);
            }

            if (admin.hasAlarm.trim() == "on") {
                $("#hasAlarm").prop("checked", true);
            } else {
                $("#hasAlarm").prop("checked", false);
            }

            $("#bosses").append();
            $("#adminName").val(admin.name.trim());
            $("#adminLastName").val(admin.lastName.trim());
            $("#adminUserName").val(admin.username.trim());
            $("#adminPassword").val(admin.password.trim());
            $("#adminPhone").val(parseInt(admin.phone.trim()));
            $("#adminDiscription").text(admin.discription.trim());
            $("#adminAddress").val(admin.address.trim());
            $("#adminSex").empty();
            sexes.forEach((element) => {
                $("#adminSex").append(element);
            });
            $("#editAdminType").empty();
            admyTypes.forEach((element) => {
                $("#editAdminType").append(element);
            });

            $("#editAdminID").val(admin.id);

            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#editProfile").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });
            $("#editProfile").modal("show");
        },
        error: function (data) {},
    });
}

$("#adminDiscription").on("blur", function (e) {
    adminId = $("#AdminForAdd").val();

    $.ajax({
        method: "get",
        url: baseUrl + "/EditAdminComment",
        data: {
            _token: "{{ csrf_token() }}",
            comment: $("#adminDiscription").val(),
            adminId: adminId,
        },
        async: true,
        success: function (arrayed_result) {},
        error: function (error) {},
    });
});

$("#searchCity").on("change", () => {
    $.ajax({
        method: "get",
        url: baseUrl + "/searchAssignRegion",
        data: {
            _token: "{{ csrf_token() }}",
            cityId: $("#searchCity").val(),
        },
        async: true,
        success: function (arrayed_result) {
            $("#searchMantagheh").empty();
            arrayed_result.forEach((element, index) => {
                $("#searchMantagheh").append(
                    `
            <option value="` +
                        element.SnMNM +
                        `">` +
                        element.NameRec +
                        `</option>
        `
                );
            });
        },
        error: function (data) {},
    });
});

$("#customerMap").on("click", () => {
    if (!$(".modal.in").length) {
        $(".modal-dialog").css({
            top: 0,
            left: 0,
        });
    }
    $("#driverLocation").modal({
        backdrop: false,
        show: true,
    });

    $(".modal-dialog").draggable({
        handle: ".modal-header",
    });
    $("#driverLocation").modal("show");
    let fsn = $("#factorSn").val();
    $.ajax({
        method: "GET",
        url: baseUrl + "/searchMapByFactor",
        data: {
            _token: "{{ csrf_token() }}",
            fsn: fsn,
        },
        async: true,
    }).then(function (data) {
        var map = L.map("map2").setView([35.70163, 51.39211], 12);
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: '<a href="https://osm.org/copyright">CRM</a>',
        }).addTo(map);
        var marker = {};
        data.forEach(function (item) {
            if (item.LatPers > 0 && item.LonPers > 0) {
                var popup = new L.popup().setContent();
                marker = L.marker([item.LonPers, item.LatPers])
                    .addTo(map)
                    .bindPopup(popup);

                let btn = document.createElement("a");
                btn.setAttribute("data-lat", item.LatPers);
                btn.setAttribute("data-lng", item.LonPers);
                btn.setAttribute("class", "map-btn");
                btn.setAttribute("target", "_blank");
                btn.setAttribute(
                    "href",
                    "https://maps.google.com/?q=" +
                        item.LonPers +
                        "," +
                        item.LatPers
                );
                btn.textContent = "مشتری";
                marker.bindPopup(btn, {
                    maxWidth: "auto",
                });
            }
        });
    });
});

$(window).load(function () {
    var currentUrl = window.location.pathname;
    if (currentUrl == "/crmDriver") {
        document.querySelector(".affairs").style.display = "none";
        document.querySelector("#publicMenu").style.display = "none";
        $(".topMenu").css({ marginTop: "-44px" });
    }
});

$("#deleteAdmin").on("click", () => {
    if ($("#AdminForAdd").val() > 0) {
        swal({
            title: "مطمئین هستید؟",
            text: "کاربر با تمام جزءیاتش حذف خواهد شد.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    method: "get",
                    url: baseUrl + "/deleteAdmin",
                    data: {
                        _token: "{{ csrf_token() }}",
                        asn: $("#AdminForAdd").val(),
                    },
                    async: true,
                    success: function (msg) {
                        $("#adminGroupList").empty();
                        msg.forEach((element, index) => {
                            let discription = "";
                            if (element.discription != null) {
                                discription = element.discription;
                            }
                            $("#adminGroupList").append(
                                `
                            <tr onclick="setAdminStuff(this)">
                            <td>` +
                                    (index + 1) +
                                    `</td>
                            <td>` +
                                    element.name +
                                    ` ` +
                                    element.lastName +
                                    `</td>
                            <td>` +
                                    element.adminType +
                                    `</td>
                            <td>` +
                                    discription +
                                    `</td>
                            <td>
                                <input class="mainGroupId" type="radio" name="AdminId[]" value="` +
                                    element.id +
                                    `_` +
                                    element.adminTypeId +
                                    `">
                            </td>
                            </tr>`
                            );
                        });
                        swal("کاربر حذف شد.", {
                            icon: "success",
                        });
                        $("#removeKarbar").modal("hide");
                    },
                    error: function (data) {},
                });
            }
        });
    }
});

function deleteAdminList() {
    if ($("#asn").val() > 0) {
        swal({
            title: "مطمئین هستید؟",
            text: "کاربر با تمام جزءیاتش حذف خواهد شد.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    method: "get",
                    url: baseUrl + "/deleteAdmin1",
                    data: {
                        _token: "{{ csrf_token() }}",
                        asn: $("#asn").val(),
                    },
                    async: true,
                    success: function (msg) {
                        $("#adminGroupList").empty();
                        msg.forEach((element, index) => {
                            let discription = "";
                            if (element.discription != null) {
                                discription = element.discription;
                            }
                            $("#adminGroupList").append(
                                `
                            <tr onclick="setAdminStuff(this)">
                            <td>` +
                                    (index + 1) +
                                    `</td>
                            <td>` +
                                    element.name +
                                    ` ` +
                                    element.lastName +
                                    `</td>
                            <td>` +
                                    element.adminType +
                                    `</td>
                            <td>` +
                                    discription +
                                    `</td>
                            <td>
                                <input class="mainGroupId" type="radio" name="AdminId[]" value="` +
                                    element.id +
                                    `_` +
                                    element.adminTypeId +
                                    `">
                            </td>
                            </tr>`
                            );
                        });
                        swal("کاربر حذف شد.", {
                            icon: "success",
                        });
                        $("#removeKarbar").modal("hide");
                    },
                    error: function (data) {},
                });
            }
        });
    }
}
$("#addedCustomerLeftSideForm").on("submit", function (e) {
    e.preventDefault();
    $.ajax({
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (arrayed_result) {
            console.log(arrayed_result);
            $("#addedCustomer").empty();
            arrayed_result.forEach((element, index) => {
                $("#addedCustomer").append(
                    `
                <tr onclick="checkCheckBox(this,event)">
                    <td id="radif" style="width:55px;">` +
                        (index + 1) +
                        `</td>
                    <td id="mCode" style="width:115px;">` +
                        element.NameRec +
                        `</td>
                    <td >` +
                        element.Name +
                        `</td>
                    <td style="width:50px;">
                        <input class="form-check-input" name="addedCustomerIDs[]" type="checkbox" value="` +
                        element.PSN +
                        `" id="kalaId">
                    </td>
                </tr>`
                );
            });
        },
        error: function (error) {},
    });
});

function saveCustomerCommentProperty(element) {
    let csn = $("#customerSn").val();

    let comment = element.value;

    $.ajax({
        method: "get",
        url: baseUrl + "/setCommentProperty",
        data: {
            _token: "{{ csrf_token() }}",
            csn: csn,
            comment: comment,
        },
        async: true,
        success: function (msg) {
            element.value = "";
            element.value = msg[0].comment;
        },
        error: function (data) {
            alert("done comment");
        },
    });
}

$("#addCustomerFirstDate").persianDatepicker({
    cellWidth: 32,
    cellHeight: 22,
    fontSize: 14,
    formatDate: "YYYY/0M/0D",
});

$("#addCustomerSecondDate").persianDatepicker({
    cellWidth: 32,
    cellHeight: 22,
    fontSize: 14,
    formatDate: "YYYY/0M/0D",
});

$("#addCustomerFristSabtDate").persianDatepicker({
    cellWidth: 32,
    cellHeight: 22,
    fontSize: 14,
    formatDate: "YYYY/0M/0D",
});

$("#addCustomerSecondSabtDate").persianDatepicker({
    cellWidth: 32,
    cellHeight: 22,
    fontSize: 14,
    formatDate: "YYYY/0M/0D",
});

$("#assesFirstDate").persianDatepicker({
    cellWidth: 32,
    cellHeight: 22,
    fontSize: 14,
    formatDate: "YYYY/0M/0D",
});

$("#assesSecondDate").persianDatepicker({
    cellWidth: 32,
    cellHeight: 22,
    fontSize: 14,
    formatDate: "YYYY/0M/0D",
});

$("#altime").persianDatepicker({
    cellWidth: 32,
    cellHeight: 22,
    fontSize: 14,
    formatDate: "0h:0m:0s YYYY/0M/0D",
    startDate: "today",
    endDate: "1440/5/5",
});
$("#firstDate").persianDatepicker({
    cellWidth: 32,
    cellHeight: 22,
    fontSize: 14,
    formatDate: "YYYY/0M/0D",
});
$("#secondDate").persianDatepicker({
    cellWidth: 32,
    cellHeight: 22,
    fontSize: 14,
    formatDate: "YYYY/0M/0D",
    onSelect: () => {
        let secondDate = $("#secondDate").val();
        let firstDate = $("#firstDate").val();
        $.ajax({
            method: "get",
            url: baseUrl + "/searchPastAssesByDate",
            data: {
                _token: "{{ csrf_token() }}",
                secondDate: secondDate,
                firstDate: firstDate,
            },
            async: true,
            success: function (msg) {
                // $('.crmDataTable').dataTable().fnDestroy();
                $("#customerListBody1").empty();
                msg.forEach((element, index) => {
                    $("#customerListBody1").append(
                        `
                <tr onclick="assesmentStuff(this)">
                <td>` +
                            (index + 1) +
                            `</td>
                <td>` +
                            element.Name +
                            `</td>
                <td class="scrollTd">` +
                            element.NetPriceHDS +
                            `</td>
                <td>` +
                            element.FactDate +
                            `</td>
                <td> <input class="customerList form-check-input" name="customerId" type="radio" value="` +
                            element.PSN +
                            `_` +
                            element.SerialNoHDS +
                            `"></td>
                </tr>`
                    );
                });
                // $('.crmDataTable').dataTable();
            },
            error: function (data) {},
        });
    },
});

$("#firstDateDoneComment").persianDatepicker({
    cellWidth: 32,
    cellHeight: 22,
    fontSize: 14,
    formatDate: "YYYY/0M/0D",
});
$("#secondDateDoneComment").persianDatepicker({
    cellWidth: 32,
    cellHeight: 22,
    fontSize: 14,
    formatDate: "YYYY/0M/0D",
});

$("#searchEmptyName").on("keyup", () => {
    let searchTerm = $("#searchEmptyName").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/searchEmptyByName",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm,
        },
        async: true,
        success: function (msg) {
            // $('.crmDataTable').dataTable().fnDestroy();
            $("#returnedCustomerList").empty();
            msg.forEach((element, index) => {
                $("#returnedCustomerList").append(
                    `
                <tr onclick="returnedCustomerStuff(this)">
                <td>` +
                        (index + 1) +
                        `</td>
                <td>` +
                        element.Name +
                        `</td>
                <td>` +
                        element.PCode +
                        `</td>
                <td>` +
                        element.peopeladdress +
                        `</td>
                <td>` +
                        element.PhoneStr +
                        `</td>
                <td>` +
                        moment(element.removedDate, "YYYY-M-D HH:mm:ss")
                            .locale("fa")
                            .format("HH:mm:ss YYYY/M/D") +
                        `</td>
                <td> <input class="customerList form-check-input" name="customerId[]" type="radio" value="` +
                        element.PSN +
                        `"></td>
            </tr>`
                );
            });
            // $('.crmDataTable').dataTable();
        },
        error: function (data) {},
    });
});

$("#searchEmptyPCode").on("keyup", () => {
    let searchTerm = $("#searchEmptyPCode").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/searchEmptyByPCode",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm,
        },
        async: true,
        success: function (msg) {
            // $('.crmDataTable').dataTable().fnDestroy();
            $("#returnedCustomerList").empty();
            msg.forEach((element, index) => {
                $("#returnedCustomerList").append(
                    `
                <tr onclick="returnedCustomerStuff(this)">
                <td>` +
                        (index + 1) +
                        `</td>
                <td>` +
                        element.Name +
                        `</td>
                <td>` +
                        element.PCode +
                        `</td>
                <td>` +
                        element.peopeladdress +
                        `</td>
                <td>` +
                        element.PhoneStr +
                        `</td>
                <td> <input class="customerList form-check-input" name="customerId[]" type="radio" value="` +
                        element.PSN +
                        `"></td>
            </tr>`
                );
            });
            // $('.crmDataTable').dataTable();
        },
        error: function (data) {},
    });
});

$("#searchAllName").on("keyup", () => {
    let searchTerm = $("#searchAllName").val();
    snMantagheh=$("#searchByMantagheh").val();
    if($(".reportRadio:checked").val()=="all"){
        $.ajax({
            method: "get",
            url: baseUrl + "/searchAllCustomerByName",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
                SnMantagheh:snMantagheh
            },
            async: true,
            success: function (msg) {
            $("#allCustomerReportyBody").empty();
            msg.forEach((element, index) => {
                $checkState="disabled";
                if(element.state==1){
                    $checkState="checked"
                }
                    $("#allCustomerReportyBody").append(`
                        <tr  onclick="setAmalkardStuff(this,`+element.PSN+`)">
                            <td >` +(index + 1) + `</td>
                            <td style="width:333px">` +element.Name + `</td>
                            <td style="width:177px">` +element.PhoneStr+`</td>
                            <td>` +element.lastDate +`</td>
                            <td>` +element.adminName+` `+element.lastName +`</td>
                            <td  style="width:66px"><input class="customerList form-check-input" name="customerId" type="radio" value="`+element.PSN+`"></td>
                            <td><input type="checkbox" `+$checkState+` /></td>
                        </tr>`);
                });
            },
            error: function (data) {},
        });
    }
    if($(".reportRadio:checked").val()=="inactive"){
        $.ajax({
            method: "get",
            url: baseUrl + "/searchInActivesByName",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
                SnMantagheh:snMantagheh
            },
            async: true,
            success: function (msg) {
                console.log(msg)
            $("#inactiveCustomerBody").empty();
            msg.forEach((element, index) => {
                    $("#inactiveCustomerBody").append(`
                                <tr onclick="setInActiveCustomerStuff(this,`+element.PSN+`)">
                                    <td>`+(index+1)+`</td>
                                    <td>`+element.CustomerName+`</td>
                                    <td  style="width:99px">`+element.PhoneStr+`</td>
                                    <td style="width:133px">`+moment(element.TimeStamp, "YYYY-M-D HH:mm:ss")
                                    .locale("fa")
                                    .format("HH:mm:ss YYYY/M/D")+`</td>
                                    <td style="width:133px">`+element.name+` `+element.lastName+`</td>
                                    <td  style="font-size:12px;">`+element.comment+`</td>
                                    <td><input class="customerList form-check-input" name="customerId" type="radio" value="`+element.PSN+`"></td>
                                </tr>`);
                });
            },
            error: function (data) {},
        });
    }
    if($(".reportRadio:checked").val()=="returned"){
        $.ajax({
            method: "get",
            url: baseUrl + "/searchReturnedByName",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
                SnMantagheh:snMantagheh
            },
            async: true,
            success: function (msg) {
                console.log(msg)
            $("#returnedCustomerList").empty();
            msg.forEach((element, index) => {
                    $("#returnedCustomerList").append(`
                            <tr onclick="returnedCustomerStuff(this,`+element.PSN+`)">
                                <td>`+(index+1)+`</td>
                                <td style="width:188px; font-size:12px">`+element.Name+`</td>
                                <td style="width:144px;">`+element.PhoneStr+`</td>
                                <td style="width:133px;">`+element.adminName+` `+element.adminLastName+`</td>
                                <td style="width:88px;">`+moment(element.returnDate, "YYYY-M-D HH:mm:ss")
                                .locale("fa")
                                .format("HH:mm:ss YYYY/M/D")+`</td>
                                <td> <input class="customerList form-check-input" name="customerId[]" type="radio" value="`+element.PSN+`_`+element.adminId+`"></td>
                            </tr>`);
                });
            },
            error: function (data) {},
        });
    }
    if($(".reportRadio:checked").val()=="noAdmin"){
        $.ajax({
            method: "get",
            url: baseUrl + "/withoutAdmins",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
                SnMantagheh:snMantagheh
            },
            async: true,
            success: function (msg) {
                console.log(msg)
            $("#evacuatedCustomers").empty();
            msg.forEach((element, index) => {
                    $("#evacuatedCustomers").append(`
                                    <tr onclick="returnedCustomerStuff(this,`+element.PSN+`)">
                                        <td>`+(index+1)+`</td>
                                        <td>`+element.Name+`</td>
                                        <td style="width:66px;">`+element.PCode+`</td>
                                        <td style="width:333px;">`+element.peopeladdress+`</r->td>
                                        <td>`+element.PhoneStr+`</td>
                                        <td> <input class="customerList form-check-input" name="customerId[]" type="radio" value="`+element.PSN+`"></td>
                                    </tr>`);
                });
            },
            error: function (data) {},
        });
    }
    if($(".reportRadio:checked").val()=="login"){
        $.ajax({
            method: "get",
            url: baseUrl + "/searchLoginsByName",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
                SnMantagheh:snMantagheh
            },
            async: true,
            success: function (msg) {
                console.log(msg)
            $("#listVisitorBody").empty();
            msg.forEach((element, index) => {
                    $("#listVisitorBody").append(`
                    <tr onclick="setAmalkardStuff(this,`+element.PSN+`)">
                        <td >`+(index+1)+`</td>
                        <td > </td>
                        <td >`+moment(element.lastVisit, "YYYY-M-D HH:mm:ss")
                        .locale("fa")
                        .format("HH:mm:ss YYYY/M/D")+`</td>
                        <td style="width:244px">`+element.Name+`</td>
                        <td >`+element.platform+`</td>
                        <td >`+element.browser+`</td>
                        <td   style="width:77px">`+element.countLogin+`</td>
                        <td>`+element.countSameTime+`</td>
                    </tr>`);
                });
            },
            error: function (data) {},
        });
    }
});

$("#searchByMantagheh").on("change", () => {
    let searchTerm = $("#searchAllName").val();
    snMantagheh=$("#searchByMantagheh").val();
    if($(".reportRadio:checked").val()=="all"){
        $.ajax({
            method: "get",
            url: baseUrl + "/searchAllCustomerByName",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
                SnMantagheh:snMantagheh
            },
            async: true,
            success: function (msg) {
            $("#allCustomerReportyBody").empty();
            msg.forEach((element, index) => {
                $checkState="disabled";
                if(element.state==1){
                    $checkState="checked"
                }
                    $("#allCustomerReportyBody").append(`
                        <tr  onclick="setAmalkardStuff(this,`+element.PSN+`)">
                            <td >` +(index + 1) + `</td>
                            <td style="width:333px">` +element.Name + `</td>
                            <td style="width:177px">` +element.PhoneStr+`</td>
                            <td>` +element.lastDate +`</td>
                            <td>` +element.adminName+` `+element.lastName +`</td>
                            <td  style="width:66px"><input class="customerList form-check-input" name="customerId" type="radio" value="`+element.PSN+`"></td>
                            <td><input type="checkbox" `+$checkState+` /></td>
                        </tr>`);
                });
            },
            error: function (data) {},
        });
    }
    if($(".reportRadio:checked").val()=="inactive"){
        $.ajax({
            method: "get",
            url: baseUrl + "/searchInActivesByName",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
                SnMantagheh:snMantagheh
            },
            async: true,
            success: function (msg) {
                console.log(msg)
            $("#inactiveCustomerBody").empty();
            msg.forEach((element, index) => {
                    $("#inactiveCustomerBody").append(`
                                <tr onclick="setInActiveCustomerStuff(this,`+element.PSN+`)">
                                    <td>`+(index+1)+`</td>
                                    <td>`+element.CustomerName+`</td>
                                    <td  style="width:99px">`+element.PhoneStr+`</td>
                                    <td style="width:133px">`+moment(element.TimeStamp, "YYYY-M-D HH:mm:ss")
                                    .locale("fa")
                                    .format("HH:mm:ss YYYY/M/D")+`</td>
                                    <td style="width:133px">`+element.name+` `+element.lastName+`</td>
                                    <td  style="font-size:12px;">`+element.comment+`</td>
                                    <td><input class="customerList form-check-input" name="customerId" type="radio" value="`+element.PSN+`"></td>
                                </tr>`);
                });
            },
            error: function (data) {},
        });
    }
    if($(".reportRadio:checked").val()=="returned"){
        $.ajax({
            method: "get",
            url: baseUrl + "/searchReturnedByName",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
                SnMantagheh:snMantagheh
            },
            async: true,
            success: function (msg) {
                console.log(msg)
            $("#returnedCustomerList").empty();
            msg.forEach((element, index) => {
                    $("#returnedCustomerList").append(`
                            <tr onclick="returnedCustomerStuff(this,`+element.PSN+`)">
                                <td>`+(index+1)+`</td>
                                <td style="width:188px; font-size:12px">`+element.Name+`</td>
                                <td style="width:144px;">`+element.PhoneStr+`</td>
                                <td style="width:133px;">`+element.adminName+` `+element.adminLastName+`</td>
                                <td style="width:88px;">`+moment(element.returnDate, "YYYY-M-D HH:mm:ss")
                                .locale("fa")
                                .format("HH:mm:ss YYYY/M/D")+`</td>
                                <td> <input class="customerList form-check-input" name="customerId[]" type="radio" value="`+element.PSN+`_`+element.adminId+`"></td>
                            </tr>`);
                });
            },
            error: function (data) {},
        });
    }
    if($(".reportRadio:checked").val()=="noAdmin"){
        $.ajax({
            method: "get",
            url: baseUrl + "/withoutAdmins",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
                SnMantagheh:snMantagheh
            },
            async: true,
            success: function (msg) {
                console.log(msg)
            $("#evacuatedCustomers").empty();
            msg.forEach((element, index) => {
                    $("#evacuatedCustomers").append(`
                                    <tr onclick="returnedCustomerStuff(this,`+element.PSN+`)">
                                        <td>`+(index+1)+`</td>
                                        <td>`+element.Name+`</td>
                                        <td style="width:66px;">`+element.PCode+`</td>
                                        <td style="width:333px;">`+element.peopeladdress+`</r->td>
                                        <td>`+element.PhoneStr+`</td>
                                        <td> <input class="customerList form-check-input" name="customerId[]" type="radio" value="`+element.PSN+`"></td>
                                    </tr>`);
                });
            },
            error: function (data) {},
        });
    }
    if($(".reportRadio:checked").val()=="login"){
        $.ajax({
            method: "get",
            url: baseUrl + "/searchLoginsByName",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
                SnMantagheh:snMantagheh
            },
            async: true,
            success: function (msg) {
                console.log(msg)
            $("#listVisitorBody").empty();
            msg.forEach((element, index) => {
                    $("#listVisitorBody").append(`
                    <tr onclick="setAmalkardStuff(this,`+element.PSN+`)">
                        <td >`+(index+1)+`</td>
                        <td > </td>
                        <td >`+moment(element.lastVisit, "YYYY-M-D HH:mm:ss")
                        .locale("fa")
                        .format("HH:mm:ss YYYY/M/D")+`</td>
                        <td style="width:244px">`+element.Name+`</td>
                        <td >`+element.platform+`</td>
                        <td >`+element.browser+`</td>
                        <td   style="width:77px">`+element.countLogin+`</td>
                        <td>`+element.countSameTime+`</td>
                    </tr>`);
                });
            },
            error: function (data) {},
        });
    }
});


$(".orderReport").on("change", () => {
    let searchTerm = $("#searchAllName").val();
    snMantagheh=$("#searchByMantagheh").val();
    let baseName;
    if($(".reportRadio:checked").val()=="all"){
        baseName=$("#orderAllCustomers").val();
        $.ajax({
            method: "get",
            url: baseUrl + "/orderAllCustomerByName",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
                SnMantagheh:snMantagheh,
                baseName
            },
            async: true,
            success: function (msg) {
            $("#allCustomerReportyBody").empty();
            msg.forEach((element, index) => {
                $checkState="disabled";
                if(element.state==1){
                    $checkState="checked"
                }
                    $("#allCustomerReportyBody").append(`
                        <tr  onclick="setAmalkardStuff(this,`+element.PSN+`)">
                            <td >` +(index + 1) + `</td>
                            <td style="width:333px">` +element.Name + `</td>
                            <td style="width:177px">` +element.PhoneStr+`</td>
                            <td>` +element.lastDate +`</td>
                            <td>` +element.adminName+` `+element.lastName +`</td>
                            <td  style="width:66px"><input class="customerList form-check-input" name="customerId" type="radio" value="`+element.PSN+`"></td>
                            <td><input type="checkbox" `+$checkState+` /></td>
                        </tr>`);
                });
            },
            error: function (data) {},
        });
    }
    if($(".reportRadio:checked").val()=="inactive"){
        baseName=$("#orderInActiveCustomers").val();
        $.ajax({
            method: "get",
            url: baseUrl + "/orderInActiveCustomers",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
                SnMantagheh:snMantagheh,
                baseName:baseName
            },
            async: true,
            success: function (msg) {
                console.log(msg)
            $("#inactiveCustomerBody").empty();
            msg.forEach((element, index) => {
                    $("#inactiveCustomerBody").append(`
                                <tr onclick="setInActiveCustomerStuff(this,`+element.PSN+`)">
                                    <td>`+(index+1)+`</td>
                                    <td>`+element.CustomerName+`</td>
                                    <td  style="width:99px">`+element.PhoneStr+`</td>
                                    <td style="width:133px">`+moment(element.TimeStamp, "YYYY-M-D HH:mm:ss")
                                    .locale("fa")
                                    .format("HH:mm:ss YYYY/M/D")+`</td>
                                    <td style="width:133px">`+element.name+` `+element.lastName+`</td>
                                    <td  style="font-size:12px;">`+element.comment+`</td>
                                    <td><input class="customerList form-check-input" name="customerId" type="radio" value="`+element.PSN+`"></td>
                                </tr>`);
                });
            },
            error: function (data) {},
        });
    }
    if($(".reportRadio:checked").val()=="returned"){
        baseName=$("#orderReportCustomers").val();
        $.ajax({
            method: "get",
            url: baseUrl + "/orderReturned",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
                SnMantagheh:snMantagheh,
                baseName:baseName
            },
            async: true,
            success: function (msg) {
                console.log(msg)
            $("#returnedCustomerList").empty();
            msg.forEach((element, index) => {
                    $("#returnedCustomerList").append(`
                            <tr onclick="returnedCustomerStuff(this,`+element.PSN+`)">
                                <td>`+(index+1)+`</td>
                                <td style="width:188px; font-size:12px">`+element.Name+`</td>
                                <td style="width:144px;">`+element.PhoneStr+`</td>
                                <td style="width:133px;">`+element.adminName+` `+element.adminLastName+`</td>
                                <td style="width:88px;">`+moment(element.returnDate, "YYYY-M-D HH:mm:ss")
                                .locale("fa")
                                .format("HH:mm:ss YYYY/M/D")+`</td>
                                <td> <input class="customerList form-check-input" name="customerId[]" type="radio" value="`+element.PSN+`_`+element.adminId+`"></td>
                            </tr>`);
                });
            },
            error: function (data) {},
        });
    }
    if($(".reportRadio:checked").val()=="noAdmin"){
        baseName=$("#orderNoAdminCustomers").val();
        $.ajax({
            method: "get",
            url: baseUrl + "/orderwithoutAdmins",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
                SnMantagheh:snMantagheh,
                baseName:baseName
            },
            async: true,
            success: function (msg) {
                console.log(msg)
            $("#evacuatedCustomers").empty();
            msg.forEach((element, index) => {
                    $("#evacuatedCustomers").append(`
                                    <tr onclick="returnedCustomerStuff(this,`+element.PSN+`)">
                                        <td>`+(index+1)+`</td>
                                        <td>`+element.Name+`</td>
                                        <td style="width:66px;">`+element.PCode+`</td>
                                        <td style="width:333px;">`+element.peopeladdress+`</r->td>
                                        <td>`+element.PhoneStr+`</td>
                                        <td> <input class="customerList form-check-input" name="customerId[]" type="radio" value="`+element.PSN+`"></td>
                                    </tr>`);
                });
            },
            error: function (data) {},
        });
    }
    if($(".reportRadio:checked").val()=="login"){
        baseName=$("#orderLoginCustomers").val();
        $.ajax({
            method: "get",
            url: baseUrl + "/orderLogins",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
                SnMantagheh:snMantagheh,
                baseName:baseName
            },
            async: true,
            success: function (msg) {
                console.log(msg)
            $("#listVisitorBody").empty();
            msg.forEach((element, index) => {
                    $("#listVisitorBody").append(`
                    <tr onclick="setAmalkardStuff(this,`+element.PSN+`)">
                        <td >`+(index+1)+`</td>
                        <td > </td>
                        <td >`+moment(element.lastVisit, "YYYY-M-D HH:mm:ss")
                        .locale("fa")
                        .format("HH:mm:ss YYYY/M/D")+`</td>
                        <td style="width:244px">`+element.Name+`</td>
                        <td >`+element.platform+`</td>
                        <td >`+element.browser+`</td>
                        <td   style="width:77px">`+element.countLogin+`</td>
                        <td>`+element.countSameTime+`</td>
                    </tr>`);
                });
            },
            error: function (data) {},
        });
    }
});

$("#searchByAdmin").on("change", () => {
    let searchTerm = $("#searchByAdmin").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/searchAllCustomerByAdmin",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm,
        },
        async: true,
        success: function (msg) {
            $("#allCustomerReportyBody").empty();
            msg.forEach((element, index) => {
                let checkOrNot = "";
                if (element.state == 1) {
                    checkOrNot = "";
                } else {
                    checkOrNot = "checked";
                }
                $("#allCustomerReportyBody").append(
                    `
                <tr  onclick="setAlarmCustomerStuff(this)">
                <td>` +
                        (index + 1) +
                        `</td>
                <td style="width:333px">` +
                        element.Name +
                        `</td>
                <td style="width:177px">` +
                        element.hamrah +
                        ` ` +
                        element.sabit +
                        `</td>
                <td>` +
                        element.lastDate +
                        `</td>
                <td>` +
                        element.adminName +
                        ` ` +
                        element.lastName +
                        `</td>
                <td style="width:66px"> <input class="customerList form-check-input" name="customerId" type="radio" value="` +
                        element.PSN +
                        `"></td>
                <td><input type="checkbox" disabled ` +
                        checkOrNot +
                        ` /></td>
            </tr>`
                );
            });
        },
        error: function (data) {},
    });
});

$("#searchAllPCode").on("keyup", () => {
    let searchTerm = $("#searchAllPCode").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/searchAllCustomerByPCode",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm,
        },
        async: true,
        success: function (msg) {
            msg.forEach((element, index) => {
                $("#allCustomerReportyBody").append(
                    `
        <tr  onclick="setAlarmCustomerStuff(this)">
        <td>` +
                        (index + 1) +
                        `</td>
        <td>` +
                        element.Name +
                        `</td>
        <td>` +
                        element.hamrah +
                        ` ` +
                        element.sabit +
                        `</td>
        <td>` +
                        element.peopeladdress +
                        `</td>
        <td>` +
                        element.countFactor +
                        `</td>
        <td>` +
                        element.lastDate +
                        `</td>
        <td>هنوز نیست</td>
        <td style="width:60px">` +
                        element.adminName +
                        ` ` +
                        element.lastName +
                        `</td>
        <td> <input class="customerList form-check-input" name="customerId" type="radio" value="` +
                        element.PSN +
                        `"></td>
    </tr>`
                );
            });
        },
        error: function (data) {},
    });
});

$("#searchAllActiveOrNot").on("change", () => {
    let searchTerm = $("#searchAllActiveOrNot").val();
    if (searchTerm != 0) {
        $.ajax({
            method: "get",
            url: baseUrl + "/searchAllCustomerActiveOrNot",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
            },
            async: true,
            success: function (msg) {
                $("#allCustomerReportyBody").empty();
                msg.forEach((element, index) => {
                    let checkOrNot = "";
                    if (element.state == 1) {
                        checkOrNot = "";
                    } else {
                        checkOrNot = "checked";
                    }
                    $("#allCustomerReportyBody").append(
                        `
            <tr  onclick="setAlarmCustomerStuff(this)">
            <td>` +
                            (index + 1) +
                            `</td>
            <td>` +
                            element.Name +
                            `</td>
            <td>` +
                            element.hamrah +
                            ` ` +
                            element.sabit +
                            `</td>
            <td>` +
                            element.peopeladdress +
                            `</td>
            <td>` +
                            element.countFactor +
                            `</td>
            <td>` +
                            element.lastDate +
                            `</td>
            <td>هنوز نیست</td>
            <td style="width:60px">` +
                            element.adminName +
                            ` ` +
                            element.lastName +
                            `</td>
            <td> <input class="customerList form-check-input" name="customerId" type="radio" value="` +
                            element.PSN +
                            `"></td>
            <td><input type="checkbox" disabled ` +
                            checkOrNot +
                            ` /></td>
        </tr>`
                    );
                });
            },
            error: function (data) {},
        });
    }
});

// $("#searchByMantagheh").on("change", () => {
//     $.ajax({
//         method: "get",
//         url: baseUrl + "/searchAllCustomerByMantagheh",
//         data: {
//             _token: "{{ csrf_token() }}",
//             searchTerm: $("#searchByMantagheh").val(),
//         },
//         async: true,
//         success: function (msg) {
//             $("#allCustomerReportyBody").empty();
//             msg.forEach((element, index) => {
//                 let checkOrNot = "";
//                 if (element.state == 1) {
//                     checkOrNot = "";
//                 } else {
//                     checkOrNot = "checked";
//                 }
//                 $("#allCustomerReportyBody").append(`
//                 <tr  onclick="setAmalkardStuff(this,`+element.PSN+`)">
//                     <td>` +(index + 1) +`</td>
//                     <td style="width:333px">` +element.Name +`</td>
//                     <td style="width:177px">` +element.PhoneStr +`</td>
//                     <td>` +element.lastDate +`</td>
//                     <td>` +element.adminName +` ` +element.lastName +`</td>
//                     <td style="width:66px"> <input class="customerList form-check-input" name="customerId" type="radio" value="` +element.PSN +`"></td>
//                     <td><input type="checkbox" disabled ` +checkOrNot +`/></td>
//                 </tr>`);
//             });
//         },
//         error: function (data) {},
//     });
// });


    function getAllCustomerInfos(){
    let locationState = $("#AllLocationOrNot").val();
    let factorState=$("#AllFactorOrNot").val();
    let basketState=$("#AllBasketOrNot").val();
    let adminId=$("#AllByAdmin").val();
        $.ajax({
            method: "get",
            url: baseUrl + "/filterAllCustomer",
            data: {
                _token: "{{ csrf_token() }}",
                locationState: locationState,
                factorState:factorState,
                basketState:basketState,
                adminId:adminId},
            async: true,
            success: function (msg) {
                console.log(msg)
                $("#allCustomerReportyBody").empty();
                msg.forEach((element, index) => {
                    let checkOrNot = "";
                    if (element.state == 1) {
                        checkOrNot = "";
                    } else {
                        checkOrNot = "checked";
                    }
                    $("#allCustomerReportyBody").append(
                        `
                <tr  onclick="setAmalkardStuff(this,`+element.PSN+`)">
                <td>` +
                            (index + 1) +
                            `</td>
                <td style="width:333px">` +
                            element.Name +
                            `</td>
                <td  style="width:177px">` +element.PhoneStr +
                            `</td>
              
                <td>` +
                            element.lastDate +
                            `</td>
                <td>` +
                            element.adminName +
                            ` ` +
                            element.lastName +
                            `</td>
                <td style="width:66px"> <input class="customerList form-check-input" name="customerId" type="radio" value="` +
                            element.PSN +
                            `"></td>
                <td><input type="checkbox" disabled ` +
                            checkOrNot +
                            ` /></td>
            </tr>`
                    );
                });
            },
            error: function (data) {},
        });
}

$("#searchAllFactorOrNot").on("change", () => {
    let searchTerm = $("#searchAllFactorOrNot").val();
    if (searchTerm > 0) {
        $.ajax({
            method: "get",
            url: baseUrl + "/searchAllCustomerFactorOrNot",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
            },
            async: true,
            success: function (msg) {
                $("#allCustomerReportyBody").empty();
                msg.forEach((element, index) => {
                    let checkOrNot = "";
                    if (element.state == 1) {
                        checkOrNot = "";
                    } else {
                        checkOrNot = "checked";
                    }
                    $("#allCustomerReportyBody").append(
                        `
                <tr  onclick="setAlarmCustomerStuff(this)">
                <td>` +
                            (index + 1) +
                            `</td>
                <td style="width:333px">` +
                            element.Name +
                            `</td>
                <td style="width:177px">` +
                            element.hamrah +
                            ` ` +
                            element.sabit +
                            `</td>
                <td>` +
                            element.lastDate +
                            `</td>
                <td>` +
                            element.adminName +
                            ` ` +
                            element.lastName +
                            `</td>
                <td style="width:66px"> <input class="customerList form-check-input" name="customerId" type="radio" value="` +
                            element.PSN +
                            `"></td>
                <td><input type="checkbox" disabled ` +
                            checkOrNot +
                            ` /></td>
            </tr>`
                    );
                });
            },
            error: function (data) {},
        });
    }
});

$("#searchAllBasketOrNot").on("change", () => {
    let searchTerm = $("#searchAllBasketOrNot").val();
    if (searchTerm > 0) {
        $.ajax({
            method: "get",
            url: baseUrl + "/searchAllCustomerBasketOrNot",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
            },
            async: true,
            success: function (msg) {
                $("#allCustomerReportyBody").empty();
                msg.forEach((element, index) => {
                    let checkOrNot = "";
                    if (element.state == 1) {
                        checkOrNot = "";
                    } else {
                        checkOrNot = "checked";
                    }
                    $("#allCustomerReportyBody").append(
                        `
                <tr  onclick="setAlarmCustomerStuff(this)">
                <td>` +
                            (index + 1) +
                            `</td>
                <td  style="width:333px">` +
                            element.Name +
                            `</td>
                <td  style="width:177px">` +
                            element.hamrah +
                            ` ` +
                            element.sabit +
                            `</td>
                <td>` +
                            element.lastDate +
                            `</td>
                <td >` +
                            element.adminName +
                            ` ` +
                            element.lastName +
                            `</td>
                <td style="width:66px"> <input class="customerList form-check-input" name="customerId" type="radio" value="` +
                            element.PSN +
                            `"></td>
                <td><input type="checkbox" disabled ` +
                            checkOrNot +
                            ` /></td>
            </tr>`
                    );
                });
            },
            error: function (data) {},
        });
    }
});

$("#searchAllLoginOrNot").on("change", () => {
    let searchTerm = $("#searchAllLoginOrNot").val();
    if (searchTerm > 0) {
        $.ajax({
            method: "get",
            url: baseUrl + "/searchAllCustomerLoginOrNot",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
            },
            async: true,
            success: function (msg) {
                $("#allCustomerReportyBody").empty();
                msg.forEach((element, index) => {
                    let checkOrNot = "";
                    if (element.state == 1) {
                        checkOrNot = "";
                    } else {
                        checkOrNot = "checked";
                    }
                    $("#allCustomerReportyBody").append(
                        `
                <tr  onclick="setAlarmCustomerStuff(this)">
                <td>` +
                            (index + 1) +
                            `</td>
                <td>` +
                            element.Name +
                            `</td>
                <td>` +
                            element.hamrah +
                            ` ` +
                            element.sabit +
                            `</td>
                <td>` +
                            element.peopeladdress +
                            `</td>
                <td>` +
                            element.countFactor +
                            `</td>
                <td>` +
                            element.lastDate +
                            `</td>
                <td>هنوز نیست</td>
                <td style="width:60px">` +
                            element.adminName +
                            ` ` +
                            element.lastName +
                            `</td>
                <td> <input class="customerList form-check-input" name="customerId" type="radio" value="` +
                            element.PSN +
                            `"></td>
                <td><input type="checkbox" disabled ` +
                            checkOrNot +
                            ` /></td>
            </tr>`
                    );
                });
            },
            error: function (data) {},
        });
    }
});

$("#searchKalaNameCode").on("keyup", () => {
    let searchTerm = $("#searchKalaNameCode").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/searchKalaNameCode",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm,
        },
        async: true,
        success: function (msg) {
            $("#kalaContainer").empty();
            msg.forEach((element, index) => {
                $("#kalaContainer").append(
                    `
            <tr>
            <td>` +
                        (index + 1) +
                        `</td>
            <td style="width:88px">` +
                        element.GoodCde +
                        `</td>
            <td style="width:333px">` +
                        element.GoodName +
                        `</td>
            <td>` +
                        element.title +
                        `</td>
            <td>` +
                        element.maxFactDate +
                        `</td>
            <td>` +
                        element.hideKala +
                        `</td>
            <td style="color:red;background-color:azure">` +
                        element.Amount +
                        `</td>
            <td>
                <input class="kala form-check-input" name="kalaId[]" type="radio" value="` +
                        element.GoodSn +
                        `" id="flexCheckCheckedKala">
            </td>
        </tr>`
                );
            });
        },
        error: function (data) {},
    });
});
$("#searchKalaStock").on("change", () => {
    let searchTerm = $("#searchKalaStock").val();
    if (searchTerm > 0) {
        $.ajax({
            method: "get",
            url: baseUrl + "/searchKalaByStock",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
            },
            async: true,
            success: function (msg) {
                $("#kalaContainer").empty();
                msg.forEach((element, index) => {
                    $("#kalaContainer").append(
                        `
                <tr>
                <td>` +
                            (index + 1) +
                            `</td>
                <td  style="width:88px">` +
                            element.GoodCde +
                            `</td>
                <td  style="width:333px">` +
                            element.GoodName +
                            `</td>
                <td>` +
                            element.maxFactDate +
                            `</td>
                <td>` +
                            element.hideKala +
                            `</td>
                <td style="color:red;background-color:azure">` +
                            element.Amount +
                            `</td>
                <td>
                    <input class="kala form-check-input" name="kalaId[]" type="radio" value="` +
                            element.GoodSn +
                            `" id="flexCheckCheckedKala">
                </td>
            </tr>`
                    );
                });
            },
            error: function (data) {},
        });
    }
});
$("#searchKalaActiveOrNot").on("change", () => {
    let searchTerm = $("#searchKalaActiveOrNot").val();
    if (searchTerm > 0) {
        $.ajax({
            method: "get",
            url: baseUrl + "/searchKalaByActiveOrNot",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
            },
            async: true,
            success: function (msg) {
                $("#kalaContainer").empty();
                msg.forEach((element, index) => {
                    $("#kalaContainer").append(
                        `
                    <tr>
                    <td>` +
                            (index + 1) +
                            `</td>
                    <td style="width:88px">` +
                            element.GoodCde +
                            `</td>
                    <td style="width:333px">` +
                            element.GoodName +
                            `</td>
                    <td>` +
                            element.maxFactDate +
                            `</td>
                    <td>` +
                            element.hideKala +
                            `</td>
                    <td style="color:red;background-color:azure">` +
                            element.Amount +
                            `</td>
                    <td>
                        <input class="kala form-check-input" name="kalaId[]" type="radio" value="` +
                            element.GoodSn +
                            `" id="flexCheckCheckedKala">
                    </td>
                </tr>`
                    );
                });
            },
            error: function (data) {},
        });
    }
});
$("#searchKalaExistInStock").on("change", () => {
    let searchTerm = $("#searchKalaExistInStock").val();
    if (searchTerm > 0) {
        $.ajax({
            method: "get",
            url: baseUrl + "/searchKalaByZeroOrNot",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
            },
            async: true,
            success: function (msg) {
                $("#kalaContainer").empty();
                msg.forEach((element, index) => {
                    $("#kalaContainer").append(
                        `
                    <tr>
                    <td>` +
                            (index + 1) +
                            `</td>
                    <td style="width:88px">` +
                            element.GoodCde +
                            `</td>
                    <td style="width:333px">` +
                            element.GoodName +
                            `</td>
                    <td>` +
                            element.maxFactDate +
                            `</td>
                    <td>` +
                            element.hideKala +
                            `</td>
                    <td style="color:red;background-color:azure">` +
                            element.Amount +
                            `</td>
                    <td>
                        <input class="kala form-check-input" name="kalaId[]" type="radio" value="` +
                            element.GoodSn +
                            `" id="flexCheckCheckedKala">
                    </td>
                </tr>`
                    );
                });
            },
            error: function (data) {},
        });
    }
});
$("#searchMainGroupKala").on("change", () => {
    let searchTerm = $("#searchMainGroupKala").val();
    if (searchTerm > 0) {
        $.ajax({
            method: "get",
            url: baseUrl + "/searchSubGroupKala",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
            },
            async: true,
            success: function (msg) {
                $("#searchSubGroupKala").empty();
                $("#searchSubGroupKala").append(`
                <option value="0" selected>همه</option>
                `);
                msg.forEach((element, index) => {
                    $("#searchSubGroupKala").append(
                        `
                    <option value="` +
                            element.id +
                            `">` +
                            element.title +
                            `</option>
                    `
                    );
                });
            },
            error: function (data) {
                alert("not GOOD");
            },
        });
    } else {
        $("#searchSubGroupKala").empty();
        $("#searchSubGroupKala").append(`
    <option value="-1" selected>--</option>
    `);
    }
});
$("#searchSubGroupKala").on("change", () => {
    let searchTerm = $("#searchSubGroupKala").val();
    if (searchTerm > 0) {
        $.ajax({
            method: "get",
            url: baseUrl + "/searchBySubGroupKala",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
            },
            async: true,
            success: function (msg) {
                $("#kalaContainer").empty();
                msg.forEach((element, index) => {
                    $("#kalaContainer").append(
                        `
                    <tr>
                    <td>` +
                            (index + 1) +
                            `</td>
                    <td>` +
                            element.GoodCde +
                            `</td>
                    <td>` +
                            element.GoodName +
                            `</td>
                    <td>` +
                            element.title +
                            `</td>
                    <td>` +
                            element.maxFactDate +
                            `</td>
                    <td>` +
                            element.hideKala +
                            `</td>
                    <td style="color:red;background-color:azure">` +
                            element.Amount +
                            `</td>
                    <td>
                        <input class="kala form-check-input" name="kalaId[]" type="radio" value="` +
                            element.GoodSn +
                            `" id="flexCheckCheckedKala">
                    </td>
                </tr>`
                    );
                });
            },
            error: function (data) {},
        });
    }
});
$("#searchAdminNameCode").on("keyup", () => {
    let searchTerm = $("#searchAdminNameCode").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/searchAdminByNameCode",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm,
        },
        async: true,
        success: function (msg) {
            $("#adminList").empty();
            msg.forEach((element, index) => {
                let adminType = "";

                if (element.adminType == 3) {
                    adminType = "بازاریاب";
                } else {
                    adminType = "پشتیبان";
                }

                $("#adminList").append(
                    `
                                    <tr onclick="setAdminStuffForAdmin(this)">
                                    <td>` +
                        (index + 1) +
                        `</td>
                                    <td>` +
                        element.name +
                        ` ` +
                        element.lastName +
                        `</td>
                                    <td>` +
                        adminType +
                        `</td>
                                    <td></td>
                                    <td>
                                        <input class="mainGroupId" type="radio" name="AdminId[]" value="` +
                        element.id +
                        `_` +
                        element.adminTypeId +
                        `">
                                    </td>
                                    </tr>`
                );
            });
        },
        error: function (data) {},
    });
});

$("#searchAdminGroup").on("change", () => {
    let searchTerm = $("#searchAdminGroup").val();
    if (searchTerm > -1) {
        $.ajax({
            method: "get",
            url: baseUrl + "/searchAdminByType",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
            },
            async: true,
            success: function (msg) {
                $("#adminList").empty();
                msg.forEach((element, index) => {
                    $("#adminList").append(
                        `
                    <tr onclick="setAdminStuffForAdmin(this)">
                    <td>` +
                            (index + 1) +
                            `</td>
                    <td>` +
                            element.name +
                            ` ` +
                            element.lastName +
                            `</td>
                    <td>` +
                            element.adminType +
                            `</td>
                    <td></td>
                    <td>
                        <input class="mainGroupId" type="radio" name="AdminId[]" value="` +
                            element.id +
                            `_` +
                            element.adminTypeId +
                            `">
                    </td>
                </tr>`
                    );
                });
            },
            error: function (data) {
                alert("not good");
            },
        });
    }
});

$("#searchAdminActiveOrNot").on("change", () => {
    let searchTerm = $("#searchAdminActiveOrNot").val();
    if (searchTerm > -1) {
        $.ajax({
            method: "get",
            url: baseUrl + "/searchAdminByActivation",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
            },
            async: true,
            success: function (msg) {
                $("#adminList").empty();
                msg.forEach((element, index) => {
                    $("#adminList").append(
                        `
                    <tr onclick="setAdminStuffForAdmin(this)">
                    <td>` +
                            (index + 1) +
                            `</td>
                    <td>` +
                            element.name +
                            ` ` +
                            element.lastName +
                            `</td>
                    <td>` +
                            element.adminType +
                            `</td>
                    <td></td>
                    <td>
                        <input class="mainGroupId" type="radio" name="AdminId[]" value="` +
                            element.id +
                            `_` +
                            element.adminTypeId +
                            `">
                    </td>
                </tr>`
                    );
                });
            },
            error: function (data) {
                alert("not good");
            },
        });
    }
});

$("#searchAdminFactorOrNot").on("change", () => {
    let searchTerm = $("#searchAdminFactorOrNot").val();
    if (searchTerm > -1) {
        $.ajax({
            method: "get",
            url: baseUrl + "/searchAdminFactorOrNot",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
            },
            async: true,
            success: function (msg) {
                $("#adminList").empty();
                msg.forEach((element, index) => {
                    $("#adminList").append(
                        `
                    <tr onclick="setAdminStuffForAdmin(this)">
                    <td>` +
                            (index + 1) +
                            `</td>
                    <td>` +
                            element.name +
                            ` ` +
                            element.lastName +
                            `</td>
                    <td>` +
                            element.adminType +
                            `</td>
                    <td></td>
                    <td>
                        <input class="mainGroupId" type="radio" name="AdminId[]" value="` +
                            element.id +
                            `_` +
                            element.adminTypeId +
                            `">
                    </td>
                </tr>`
                    );
                });
            },
            error: function (data) {
                alert("not good");
            },
        });
    }
});

$("#searchAdminLoginOrNot").on("change", () => {
    let searchTerm = $("#searchAdminLoginOrNot").val();
    if (searchTerm > -1) {
        $.ajax({
            method: "get",
            url: baseUrl + "/searchAdminLoginOrNot",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
            },
            async: true,
            success: function (msg) {
                $("#adminList").empty();
                msg.forEach((element, index) => {
                    $("#adminList").append(
                        `
                    <tr onclick="setAdminStuffForAdmin(this)">
                    <td>` +
                            (index + 1) +
                            `</td>
                    <td>` +
                            element.name +
                            ` ` +
                            element.lastName +
                            `</td>
                    <td>` +
                            element.adminType +
                            `</td>
                    <td>` +
                            element.discription +
                            `</td>
                    <td>
                        <input class="mainGroupId" type="radio" name="AdminId[]" value="` +
                            element.id +
                            `_` +
                            element.adminTypeId +
                            `">
                    </td>
                </tr>`
                    );
                });
            },
            error: function (data) {
                alert("not good");
            },
        });
    }
});

$("#searchAdminCustomerLoginOrNot").on("change", () => {
    let searchTerm = $("#searchAdminCustomerLoginOrNot").val();
    if (searchTerm > -1) {
        $.ajax({
            method: "get",
            url: baseUrl + "/searchAdminCustomerLoginOrNot",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
            },
            async: true,
            success: function (msg) {
                $("#adminList").empty();
                msg.forEach((element, index) => {
                    $("#adminList").append(
                        `
                <tr onclick="setAdminStuffForAdmin(this)">
                <td>` +
                            (index + 1) +
                            `</td>
                <td>` +
                            element.name +
                            ` ` +
                            element.lastName +
                            `</td>
                <td>` +
                            element.adminType +
                            `</td>
                <td>` +
                            element.discription +
                            `</td>
                <td>
                    <input class="mainGroupId" type="radio" name="AdminId[]" value="` +
                            element.id +
                            `_` +
                            element.adminTypeId +
                            `">
                </td>
            </tr>`
                    );
                });
            },
            error: function (data) {
                alert("not good");
            },
        });
    }
});

$("#searchInActiveByName").on("keyup", () => {
    let searchTerm = $("#searchInActiveByName").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/searchInActiveCustomerByName",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm,
        },
        async: true,
        success: function (msg) {
            $("#inactiveCustomerBody").empty();
            msg.forEach((element, index) => {
                $("#inactiveCustomerBody").append(
                    `
            <tr onclick="setInActiveCustomerStuff(this,`+element.PSN+`)">
            <td>` +
                        (index + 1) +
                        `</td>
            <td>` +
                        element.CustomerName +
                        `</td>
            <td>` +
                        element.PhoneStr +
                        `</td>
            <td>` +
                        moment(element.TimeStamp, "YYYY-M-D HH:mm:ss")
                            .locale("fa")
                            .format("HH:mm:ss YYYY/M/D") +
                        `</td>
            <td>` +
                        element.name +
                        ` ` +
                        element.lastName +
                        `</td>
            <td>بدست نیامده</td>
            <td>` +
                        element.comment +
                        `</td>
            <td><input class="customerList form-check-input" name="customerId" type="radio" value="` +
                        element.PSN +
                        `"></td>
        </tr>`
                );
            });
        },
        error: function (data) {
            alert("not good");
        },
    });
});

$("#searchInActiveByCode").on("keyup", () => {
    let searchTerm = $("#searchInActiveByCode").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/searchInActiveCustomerByCode",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm,
        },
        async: true,
        success: function (msg) {
            $("#inactiveCustomerBody").empty();
            msg.forEach((element, index) => {
                $("#inactiveCustomerBody").append(
                    `
            <tr onclick="setInActiveCustomerStuff(this)">
            <td>` +
                        (index + 1) +
                        `</td>
            <td>` +
                        element.CustomerName +
                        `</td>
            <td style="width:99px">` +
                        element.PhoneStr +
                        `</td>
            <td style="width:133px">` +
                        moment(element.TimeStamp, "YYYY-M-D HH:mm:ss")
                            .locale("fa")
                            .format("HH:mm:ss YYYY/M/D") +
                        `</td>
            <td style="width:133px">` +
                        element.name +
                        ` ` +
                        element.lastName +
                        `</td>
            <td>` +
                        element.comment +
                        `</td>
            <td><input class="customerList form-check-input" name="customerId" type="radio" value="` +
                        element.PSN +
                        `"></td>
        </tr>`
                );
            });
        },
        error: function (data) {
            alert("not good");
        },
    });
});

$("#searchInActiveByLocation").on("change", () => {
    let searchTerm = $("#searchInActiveByLocation").val();
    if (searchTerm > -1) {
        $.ajax({
            method: "get",
            url: baseUrl + "/searchInActiveCustomerByLocation",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
            },
            async: true,
            success: function (msg) {
                $("#inactiveCustomerBody").empty();
                msg.forEach((element, index) => {
                    $("#inactiveCustomerBody").append(
                        `
                <tr onclick="setInActiveCustomerStuff(this)">
                <td>` +
                            (index + 1) +
                            `</td>
                <td>` +
                            element.CustomerName +
                            `</td>
                <td style="width:99px">` +
                            element.PhoneStr +
                            `</td>
                <td style="width:133px">` +
                            moment(element.TimeStamp, "YYYY-M-D HH:mm:ss")
                                .locale("fa")
                                .format("HH:mm:ss YYYY/M/D") +
                            `</td>
                <td style="width:133px">` +
                            element.name +
                            ` ` +
                            element.lastName +
                            `</td>
                <td>` +
                            element.comment +
                            `</td>
                <td><input class="customerList form-check-input" name="customerId" type="radio" value="` +
                            element.PSN +
                            `"></td>
            </tr>`
                    );
                });
            },
            error: function (data) {
                alert("not good");
            },
        });
    }
});

$("#filterInActivesBtn").on("click",function(){
    let inactiverAdmin=$("#inactiverAdmin").val();
    let boughtState=$("#boughtState").val();
    
    $.ajax({
        method: "get",
        url: baseUrl + "/filterInactiveCustomers",
        data: {
            _token: "{{ csrf_token() }}",
            inactiverAdmin: inactiverAdmin,
            boughtState: boughtState
        },
        async: true,
        success: function (msg) {
            console.log(msg)
            $("#inactiveCustomerBody").empty();
            msg.forEach((element, index) => {
                $("#inactiveCustomerBody").append(
                    `
            <tr onclick="setInActiveCustomerStuff(this)">
            <td>` +
                        (index + 1) +
                        `</td>
            <td>` +
                        element.CustomerName +
                        `</td>
            <td style="width:99px">` +
                        element.PhoneStr +
                        `</td>
            <td style="width:133px">` +
                        moment(element.TimeStamp, "YYYY-M-D HH:mm:ss")
                            .locale("fa")
                            .format("HH:mm:ss YYYY/M/D") +
                        `</td>
            <td style="width:133px">` +
                        element.name +
                        ` ` +
                        element.lastName +
                        `</td>
            <td>` +
                        element.comment +
                        `</td>
            <td><input class="customerList form-check-input" name="customerId" type="radio" value="` +
                        element.PSN +
                        `"></td>
        </tr>`
                );
            });
        },
        error:function(error){

    }
});
});

$("#filterNoAdminsBtn").on("click",function(){
    let boughtState=$("#buyOrNot").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/filterNoAdmins",
        data: {
            _token: "{{ csrf_token() }}",
            boughtState: boughtState
        },
        async: true,
        success: function (msg) {
            console.log(msg)
            $("#evacuatedCustomers").empty();
            msg.forEach((element, index) => {
                    $("#evacuatedCustomers").append(`
                                    <tr onclick="returnedCustomerStuff(this,`+element.PSN+`)">
                                        <td>`+(index+1)+`</td>
                                        <td>`+element.Name+`</td>
                                        <td style="width:66px;">`+element.PCode+`</td>
                                        <td style="width:333px;">`+element.peopeladdress+`</td>
                                        <td>`+element.PhoneStr+`</td>
                                        <td>`+element.LastDate+`</td>
                                        <td> <input class="customerList form-check-input" name="customerId[]" type="radio" value="`+element.PSN+`"></td>
                                    </tr>`);
                });
        }
        ,error:function(error){}
    });
    
});

$("#filterReturnedsBtn").on("click",function(){
    buyState=$("#buyState").val();
    returner=$("#returner").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/filterReturneds",
        data: {
            _token: "{{ csrf_token() }}",
            buyState: buyState,
            returner:returner
        },
        async: true,
        success: function (msg) {
            $("#returnedCustomerList").empty();
            msg.forEach((element, index) => {
            $("#returnedCustomerList").append(`
                <tr onclick="returnedCustomerStuff(this,`+element.PSN+`)">
                    <td>`+(index+1)+`</td>
                    <td style="width:188px; font-size:12px">`+element.Name+`</td>
                    <td style="width:144px;">`+element.PhoneStr+`</td>
                    <td style="width:133px;">`+element.adminName+` `+element.adminLastName+`</td>
                    <td style="width:88px;">`+moment(element.returnDate, "YYYY-M-D HH:mm:ss")
                    .locale("fa")
                    .format("HH:mm:ss YYYY/M/D")+`</td>
                    <td> <input class="customerList form-check-input" name="customerId[]" type="radio" value="`+element.PSN+`_`+element.adminId+`"></td>
                </tr>`);
            });
        },
        error:function(error){
        }
    });
});

$("#addProvincePhoneCode").on("submit", function (e) {
    $.ajax({
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (data) {
            $("#countryCodeModal").modal("hide");
            $("#PhoneCode").empty();
            data.forEach((element) => {
                $("#PhoneCode").append(
                    `<option value="` +
                        element.provinceCode +
                        `">` +
                        element.provinceCode +
                        `</option>`
                );
            });
        },
        error: function (err) {
            alert("کد اضافه نشد.");
        },
    });
    e.preventDefault();
});

$("#orderInactiveCustomers").on("change", () => {
    let searchTerm = $("#orderInactiveCustomers").val();
    if (searchTerm > -1) {
        $.ajax({
            method: "get",
            url: baseUrl + "/orderInactiveCustomers",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
            },
            async: true,
            success: function (msg) {
                $("#inactiveCustomerBody").empty();
                msg.forEach((element, index) => {
                    $("#inactiveCustomerBody").append(
                        `
                <tr onclick="setInActiveCustomerStuff(this)">
                <td>` +
                            (index + 1) +
                            `</td>
                <td>` +
                            element.CustomerName +
                            `</td>
                <td style="width:99px">` +
                            element.PhoneStr +
                            `</td>
                <td style="width:133px">` +
                            moment(element.TimeStamp, "YYYY-M-D HH:mm:ss")
                                .locale("fa")
                                .format("HH:mm:ss YYYY/M/D") +
                            `</td>
                <td style="width:133px">` +
                            element.name +
                            ` ` +
                            element.lastName +
                            `</td>
                <td>` +
                            element.comment +
                            `</td>
                <td><input class="customerList form-check-input" name="customerId" type="radio" value="` +
                            element.PSN +
                            `"></td>
            </tr>`
                    );
                });
            },
            error: function (data) {
                alert("not good");
            },
        });
    }
});

function getLoginReport(history) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getHistroyLogins",
        data: {
            _token: "{{ csrf_token() }}",
            history:""+history+""
        },
        async: true,
        success: function (msg) {
            console.log(msg)
            $("#listVisitorBody").empty();
            msg.forEach((element, index) => {
                $("#listVisitorBody").append(
                    `<tr onclick="setAmalkardStuff(`+element.PSN+`)">
            <td >` +
                        (index + 1) +
                        `</td>
            <td >  </td>
            <td >` +moment(element.lastVisit, "YYYY-M-D HH:mm:ss")
                            .locale("fa")
                            .format("D/M/YYYY HH:mm:ss") +
                        `</td>
            <td style="width:244px">` +
                        element.Name +
                        `</td>
            <td >` +
                        element.platform +
                        `</td>
            <td >` +
                        element.browser +
                        `</td>
            <td style="width:77px">` +
                        element.countLogin +
                        `</td>
            <td >` +
                        element.countSameTime +
                        `</td>
            </tr>`
                );
            });
        },
        error: function (data) {
            alert("bad");
        },
    });
}
function getReferencialReport(history) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getReferencialReport",
        data: {
            _token: "{{ csrf_token() }}",
            history: history
        },
        async: true,
        success: function (msg) {
            $("#returnedCustomerList").empty();
            msg.forEach((element, index) => {
            $("#returnedCustomerList").append(`
                <tr onclick="returnedCustomerStuff(this,`+element.PSN+`)">
                    <td>`+(index+1)+`</td>
                    <td style="width:188px; font-size:12px">`+element.Name+`</td>
                    <td style="width:144px;">`+element.PhoneStr+`</td>
                    <td style="width:133px;">`+element.adminName+` `+element.adminLastName+`</td>
                    <td style="width:88px;">`+moment(element.returnDate, "YYYY-M-D HH:mm:ss")
                    .locale("fa")
                    .format("HH:mm:ss YYYY/M/D")+`</td>
                    <td> <input class="customerList form-check-input" name="customerId[]" type="radio" value="`+element.PSN+`_`+element.adminId+`"></td>
                </tr>`);
            });
        },
        error:function(error){
        }
    });
}
function getInactiveReport(history) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getInactiveReport",
        data: {
            _token: "{{ csrf_token() }}",
            history: history
        },
        async: true,
        success: function (msg) {
            console.log(msg)
        $("#inactiveCustomerBody").empty();
        msg.forEach((element, index) => {
                $("#inactiveCustomerBody").append(`
                            <tr onclick="setInActiveCustomerStuff(this,`+element.PSN+`)">
                                <td>`+(index+1)+`</td>
                                <td>`+element.CustomerName+`</td>
                                <td  style="width:99px">`+element.PhoneStr+`</td>
                                <td style="width:133px">`+moment(element.TimeStamp, "YYYY-M-D HH:mm:ss")
                                .locale("fa")
                                .format("HH:mm:ss YYYY/M/D")+`</td>
                                <td style="width:133px">`+element.name+` `+element.lastName+`</td>
                                <td  style="font-size:12px;">`+element.comment+`</td>
                                <td><input class="customerList form-check-input" name="customerId" type="radio" value="`+element.PSN+`"></td>
                            </tr>`);
            });
        },
        error: function (data) {},
    });
}

$("#searchByReturner").on("change", () => {
    let searchTerm = $("#searchByReturner").val();
    if (searchTerm != 0) {
        $.ajax({
            method: "get",
            url: baseUrl + "/searchByReturner",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
            },
            async: true,
            success: function (msg) {
                // $('.crmDataTable').dataTable().fnDestroy();
                moment.locale("en");
                $("#returnedCustomerList").empty();
                msg.forEach((element, index) => {
                    $("#returnedCustomerList").append(
                        `
                <tr onclick="returnedCustomerStuff(this)">
                    <td>` +
                            (index + 1) +
                            `</td>
                    <td>` +
                            element.Name +
                            `</td>
                    <td>` +
                            element.PCode +
                            `</td>
                    <td class="scrollTd">` +
                            element.peopeladdress +
                            `</td>
                    <td>` +
                            element.hamrah +
                            `</td>
                    <td>` +
                            element.adminName +
                            ` ` +
                            element.adminLastName +
                            `</td>
                    <td>` +
                            moment(element.returnDate, "YYYY-M-D HH:mm:ss")
                                .locale("fa")
                                .format("HH:mm:ss YYYY/M/D") +
                            `</td>
                    <td> <input class="customerList form-check-input" name="customerId[]" type="radio" value="` +
                            element.PSN +
                            ` ` +
                            element.adminId +
                            `"></td>
                </tr> `
                    );
                });
                // $('.crmDataTable').dataTable();
            },
            error: function (data) {},
        });
    }
});

$("#commentDate").persianDatepicker({
    cellWidth: 40,
    cellHeight: 22,
    fontSize: 12,
    formatDate: "0h:0m:0s YYYY/0M/0D",
    startDate: "today",
    endDate: "1440/5/5",
});

$("#commentDate2").persianDatepicker({
    cellWidth: 40,
    cellHeight: 22,
    fontSize: 12,
    formatDate: "YYYY/0M/0D",
    startDate: "today",
    endDate: "1440/5/5",
});

$("#commentDate3").persianDatepicker({
    cellWidth: 40,
    cellHeight: 22,
    fontSize: 12,
    formatDate: "YYYY/0M/0D",
    startDate: "today",
    endDate: "1440/5/5",
});
$("#LoginDate2").persianDatepicker({
    cellWidth: 40,
    cellHeight: 22,
    fontSize: 12,
    formatDate: "YYYY/0M/0D",
    onSelect: () => {
        let secondDate = $("#LoginDate2").val();
        let firstDate = $("#LoginDate1").val();
        if (firstDate) {
            $.ajax({
                method: "get",
                url: baseUrl + "/searchVisotrsByDate",
                data: {
                    _token: "{{ csrf_token() }}",
                    secondDate: secondDate,
                    firstDate: firstDate,
                },
                async: true,
                success: function (msg) {
                    $("#listVisitorBody").empty();
                    msg.forEach((element, index) => {
                        $("#listVisitorBody").append(
                            `<tr>
                <td >` +
                                (index + 1) +
                                `</td>
                <td > </td>
                <td >` +
                                moment(element.lastVisit, "YYYY-M-D HH:mm:ss")
                                    .locale("fa")
                                    .format("D/M/YYYYY HH:mm:ss") +
                                `</td>
                <td style="width:244px">` +
                                element.Name +
                                `</td>
                <td >` +
                                element.platform +
                                `</td>
                <td >` +
                                element.browser +
                                `</td>
                <td style="width:77px">` +
                                element.countLogin +
                                `</td>
                <td >` +
                                element.countSameTime +
                                `</td>
                </tr>`
                        );
                    });
                },
                error: function (data) {
                    alert("bad");
                },
            });
        }
    },
});

$("#LoginFrom").on("keyup", function () {
    $.ajax({
        method: "get",
        url: baseUrl + "/searchVisotrsLoginFrom",
        data: {
            _token: "{{ csrf_token() }}",
            loginFrom: $("#LoginFrom").val(),
        },
        async: true,
        success: function (msg) {
            $("#listVisitorBody").empty();
            msg.forEach((element, index) => {
                $("#listVisitorBody").append(
                    `<tr>
            <td >` +
                        (index + 1) +
                        `</td>
            <td > </td>
            <td >` +
                        moment(element.lastVisit, "YYYY-M-D HH:mm:ss")
                            .locale("fa")
                            .format("D/M/YYYY HH:mm:ss") +
                        `</td>
            <td style="width:244px">` +
                        element.Name +
                        `</td>
            <td >` +
                        element.platform +
                        `</td>
            <td >` +
                        element.browser +
                        `</td>
            <td style="width:77px">` +
                        element.countLogin +
                        `</td>
            <td >` +
                        element.countSameTime +
                        `</td>
            </tr>`
                );
            });
        },
        error: function (data) {
            alert("bad");
        },
    });
});

$("#filterAllLoginsBtn").on("click", function () {
    $.ajax({
        method: "get",
        url: baseUrl + "/filterAllLogins",
        data: {
            _token: "{{ csrf_token() }}",
            platform: $("#visitorPlatform").val(),
            countLoginFrom:$("#LoginFrom").val(),
            countLoginTo:$("#LoginTo").val(),
            countSameTimeFrom:$("#countSameTime").val(),
            countSameTimeTo:$("#countSameTimeTo").val(),
            firstDate:$("#LoginDate1").val(),
            secondDate:$("#LoginDate2").val()
        },
        async: true,
        success: function (msg) {
            console.log(msg)
            $("#listVisitorBody").empty();
            msg.forEach((element, index) => {
                $("#listVisitorBody").append(
                    `<tr>
            <td >` +
                        (index + 1) +
                        `</td>
            <td >  </td>
            <td >` +moment(element.lastVisit, "YYYY-M-D HH:mm:ss")
                            .locale("fa")
                            .format("D/M/YYYY HH:mm:ss") +
                        `</td>
            <td style="width:244px">` +
                        element.Name +
                        `</td>
            <td >` +
                        element.platform +
                        `</td>
            <td >` +
                        element.browser +
                        `</td>
            <td style="width:77px">` +
                        element.countLogin +
                        `</td>
            <td >` +
                        element.countSameTime +
                        `</td>
            </tr>`
                );
            });
        },
        error: function (data) {
            alert("bad");
        },
    });
});

$("#countSameTime").on("keyup", function () {
    if ($("#countSameTime").val()) {
        $.ajax({
            method: "get",
            url: baseUrl + "/searchSameTimeCountLogin",
            data: {
                _token: "{{ csrf_token() }}",
                countSameTimeLogin: $("#countSameTime").val(),
            },
            async: true,
            success: function (msg) {
                $("#listVisitorBody").empty();
                msg.forEach((element, index) => {
                    $("#listVisitorBody").append(
                        `<tr>
            <td >` +
                            (index + 1) +
                            `</td>
            <td > </td>
            <td >` +
                            moment(element.lastVisit, "YYYY-M-D HH:mm:ss")
                                .locale("fa")
                                .format("D/M/YYYY HH:mm:ss") +
                            `</td>
            <td style="width:244px">` +
                            element.Name +
                            `</td>
            <td >` +
                            element.platform +
                            `</td>
            <td >` +
                            element.browser +
                            `</td>
            <td style="width:77px">` +
                            element.countLogin +
                            `</td>
            <td >` +
                            element.countSameTime +
                            `</td>
            </tr>`
                    );
                });
            },
            error: function (data) {
                alert("bad");
            },
        });
    }
});

$("#LoginTo").on("keyup", function () {
    $.ajax({
        method: "get",
        url: baseUrl + "/searchVisotrsLoginTo",
        data: {
            _token: "{{ csrf_token() }}",
            loginTo: $("#LoginTo").val(),
        },
        async: true,
        success: function (msg) {
            $("#listVisitorBody").empty();
            msg.forEach((element, index) => {
                $("#listVisitorBody").append(
                    `<tr>
            <td >` +
                        (index + 1) +
                        `</td>
            <td > </td>
            <td >` +
                        moment(element.lastVisit, "YYYY-M-D HH:mm:ss")
                            .locale("fa")
                            .format("D/M/YYYY HH:mm:ss") +
                        `</td>
            <td style="width:244px">` +
                        element.Name +
                        `</td>
            <td >` +
                        element.platform +
                        `</td>
            <td >` +
                        element.browser +
                        `</td>
            <td style="width:77px">` +
                        element.countLogin +
                        `</td>
            <td >` +
                        element.countSameTime +
                        `</td>
            </tr>`
                );
            });
        },
        error: function (data) {
            alert("bad");
        },
    });
});

$("#LoginDate1").persianDatepicker({
    cellWidth: 40,
    cellHeight: 22,
    fontSize: 12,
    formatDate: "YYYY/0M/0D",
    onSelect: () => {
        let secondDate = $("#LoginDate2").val();
        let firstDate = $("#LoginDate1").val();
        if (secondDate) {
            $.ajax({
                method: "get",
                url: baseUrl + "/searchVisotrsByDate",
                data: {
                    _token: "{{ csrf_token() }}",
                    secondDate: secondDate,
                    firstDate: firstDate,
                },
                async: true,
                success: function (msg) {
                    $("#listVisitorBody").empty();
                    msg.forEach((element, index) => {
                        $("#listVisitorBody").append(
                            `<tr>
                <td >` +
                                (index + 1) +
                                `</td>
                <td > </td>
                <td >` +
                                moment(element.lastVisit, "YYYY-M-D HH:mm:ss")
                                    .locale("fa")
                                    .format("D/M/YYYYY HH:mm:ss") +
                                `</td>
                <td style="width:244px">` +
                                element.Name +
                                `</td>
                <td >` +
                                element.platform +
                                `</td>
                <td >` +
                                element.browser +
                                `</td>
                <td style="width:77px">` +
                                element.countLogin +
                                `</td>
                <td >` +
                                element.countSameTime +
                                `</td>
                </tr>`
                        );
                    });
                },
                error: function (data) {
                    alert("bad");
                },
            });
        }
    },
});

$("#commentDate1").persianDatepicker({
    cellWidth: 40,
    cellHeight: 22,
    fontSize: 12,
    formatDate: "YYYY/0M/0D",
    startDate: "today",
    endDate: "1440/5/5",
});

$("#searchAllCName").on("keyup", function () {
    let searchTerm1 = $("#searchAllCName").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/searchAllCustomerByName",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm1,
        },
        async: true,
        success: function (msg) {
            // $('.crmDataTable').dataTable().fnDestroy();
            $("#customerListBody1").empty();
            msg.forEach((element, index) => {
                $("#customerListBody1").append(
                    `
            <tr onclick="selectAndHighlight(this)">
            <td>` +
                        (index + 1) +
                        `</td>
            <td>` +
                        element.PCode +
                        `</td>
            <td>` +
                        element.Name +
                        `</td>
            <td  class="scrollTd">` +
                        element.peopeladdress +
                        `</td>
            <td>` +
                        element.sabit +
                        `</td>
            <td>` +
                        element.hamrah +
                        `</td>
            <td>` +
                        element.NameRec +
                        `</td>
            <td>2</td>
            <td> <input class="customerList form-check-input" name="customerId" type="radio" value="` +
                        element.PSN +
                        `_` +
                        element.GroupCode +
                        `"></td>
            </tr>`
                );
            });
            // $('.crmDataTable').dataTable();
        },
        error: function (data) {},
    });
});

$("#searchAllCCode").on("keyup", function () {
    let searchTerm1 = $("#searchAllCCode").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/searchAllCustomerByCode",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm1,
        },
        async: true,
        success: function (msg) {
            // $('.crmDataTable').dataTable().fnDestroy();
            $("#customerListBody1").empty();
            msg.forEach((element, index) => {
                $("#customerListBody1").append(
                    `
            <tr onclick="selectAndHighlight(this)">
            <td>` +
                        (index + 1) +
                        `</td>
            <td>` +
                        element.PCode +
                        `</td>
            <td>` +
                        element.Name +
                        `</td>
            <td  class="scrollTd">` +
                        element.peopeladdress +
                        `</td>
            <td>` +
                        element.sabit +
                        `</td>
            <td>` +
                        element.hamrah +
                        `</td>
            <td>` +
                        element.NameRec +
                        `</td>
            <td>2</td>
            <td> <input class="customerList form-check-input" name="customerId" type="radio" value="` +
                        element.PSN +
                        `_` +
                        element.GroupCode +
                        `"></td>
            </tr>`
                );
            });
            // $('.crmDataTable').dataTable();
        },
        error: function (data) {},
    });
});

$("#orderAllByCName").on("change", function () {
    let searchTerm1 = $("#orderAllByCName").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/orderAllCustomerByCName",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm1,
        },
        async: true,
        success: function (msg) {
            // $('.crmDataTable').dataTable().fnDestroy();
            $("#customerListBody1").empty();
            msg.forEach((element, index) => {
                $("#customerListBody1").append(
                    `
            <tr onclick="selectAndHighlight(this)">
            <td>` +
                        (index + 1) +
                        `</td>
            <td>` +
                        element.PCode +
                        `</td>
            <td>` +
                        element.Name +
                        `</td>
            <td  class="scrollTd">` +
                        element.peopeladdress +
                        `</td>
            <td>` +
                        element.sabit +
                        `</td>
            <td>` +
                        element.hamrah +
                        `</td>
            <td>` +
                        element.NameRec +
                        `</td>
            <td>2</td>
            <td> <input class="customerList form-check-input" name="customerId" type="radio" value="` +
                        element.PSN +
                        `_` +
                        element.GroupCode +
                        `"></td>
            </tr>`
                );
            });
            // $('.crmDataTable').dataTable();
        },
        error: function (data) {},
    });
});

$("#searchCustomerName").on("keyup", function () {
    let searchTerm1 = $("#searchCustomerName").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/searchCustomerByName",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm1,
        },
        async: true,
        success: function (msg) {
            console.log(msg)
            // $('.crmDataTable').dataTable().fnDestroy();
            $("#customerListBody1").empty();
            msg.forEach((element, index) => {
                let backgroundColor = "";
                if (element.countComment > 0) {
                    backgroundColor = "lightblue";
                }
                $("#customerListBody1").append(
                    `
            <tr onclick="selectAndHighlight(this)" style="background-color:` +
                        backgroundColor +
                        `">
            <td>` +
                        (index + 1) +
                        `</td>
            <td>` +
                        element.PCode +
                        `</td>
            <td>` +
                        element.Name +
                        `</td>
            <td  class="scrollTd">` +
                        element.peopeladdress +
                        `</td>
            <td>` +
                        element.sabit +
                        `</td>
            <td>` +
                        element.hamrah +
                        `</td>
            <td>` +
                        element.NameRec +
                        `</td>
            <td>2</td>
            <td> <input class="customerList form-check-input" name="customerId" type="radio" value="` +
                        element.PSN +
                        `_` +
                        element.GroupCode +
                        `"></td>
            </tr>`
                );
            });
            // $('.crmDataTable').dataTable();
        },
        error: function (data) {},
    });
});
$("#searchReferedName").on("keyup", () => {
    let searchTerm1 = $("#searchReferedName").val();
    if (searchTerm1.length > 0) {
        $.ajax({
            method: "get",
            url: baseUrl + "/searchReferedCustomerName",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm1,
            },
            async: true,
            success: function (msg) {
                // $('.crmDataTable').dataTable().fnDestroy();
                $("#returnedCustomerList").empty();
                msg.forEach((element, index) => {
                    $("#returnedCustomerList").append(
                        `
            <tr  onclick="returnedCustomerStuff(this)">
                <td>` +
                            (index + 1) +
                            `</td>
                <td>` +
                            element.Name +
                            `</td>
                <td>` +
                            element.PCode +
                            `</td>
                <td>` +
                            element.peopeladdress +
                            `</td>
                <td>` +
                            element.hamrah +
                            `</td>
                <td>` +
                            element.adminName +
                            ` ` +
                            element.adminLastName +
                            `</td>
                <td>` +
                            element.returnDate +
                            `</td>
                <td> <input class="customerList form-check-input" name="customerId[]" type="radio" value="` +
                            element.PSN +
                            `_` +
                            element.adminId +
                            `"></td>
            </tr>`
                    );
                });
                // $('.crmDataTable').dataTable();
            },
            error: function (data) {
                alert("bad");
            },
        });
    }
});

$("#searchPCode").on("keyup", () => {
    let searchTerm1 = $("#searchPCode").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/searchReferedPCode",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm1,
        },
        async: true,
        success: function (msg) {
            $("#returnedCustomerList").empty();
            msg.forEach((element, index) => {
                $("#returnedCustomerList").append(
                    `
                <tr onclick="returnedCustomerStuff(this)">
                    <td>` +
                        (index + 1) +
                        `</td>
                    <td>` +
                        element.Name +
                        `</td>
                    <td style="width:66px;">` +
                        element.PCode +
                        `</td>
                    <td class="scrollTd">` +
                        element.peopeladdress +
                        `</td>
                    <td>` +
                        element.PhoneStr +
                        `</td>
                    <td>` +
                        element.adminName +
                        ` ` +
                        element.adminLastName +
                        `</td>
                    <td> <input class="customerList form-check-input" name="customerId[]" type="radio" value="` +
                        element.PSN +
                        `_` +
                        element.adminId +
                        `"></td>
                </tr>`
                );
            });
        },
        error: function (data) {},
    });
});
$("#searchCustomerCode").on("keyup", function () {
    let searchTerm1 = $("#searchCustomerCode").val();
    if (searchTerm1.length > 0) {
        $.ajax({
            method: "get",
            url: baseUrl + "/searchCustomerByCode",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm1,
            },
            async: true,
            success: function (msg) {
                $("#customerListBody1").empty();
                msg.forEach((element, index) => {
                    let backgroundColor = "";
                    if (element.countComment > 0) {
                        backgroundColor = "lightblue";
                    }
                    $("#customerListBody1").append(
                        `
            <tr onclick="selectAndHighlight(this)" style="background-color:` +
                            backgroundColor +
                            `">
            <td>` +
                            (index + 1) +
                            `</td>
            <td>` +
                            element.PCode +
                            `</td>
            <td>` +
                            element.Name +
                            `</td>
            <td  class="scrollTd">` +
                            element.peopeladdress +
                            `</td>
            <td>` +
                            element.sabit +
                            `</td>
            <td>` +
                            element.hamrah +
                            `</td>
            <td>` +
                            element.NameRec +
                            `</td>
            <td>2</td>
            <td> <input class="customerList form-check-input" name="customerId" type="radio" value="` +
                            element.PSN +
                            `_` +
                            element.GroupCode +
                            `"></td>
            </tr>`
                    );
                });
            },
            error: function (data) {},
        });
    }
});

$("#orderByCodeOrName").on("change", () => {
    let searchTerm1 = $("#orderByCodeOrName").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/orderByNameCode",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm1,
        },
        async: true,
        success: function (msg) {
            $("#customerListBody1").empty();
            msg.forEach((element, index) => {
                let backgroundColor = "";
                if (element.countComment > 0) {
                    backgroundColor = "lightblue";
                }
                $("#customerListBody1").append(
                    `
            <tr onclick="selectAndHighlight(this)" style="background-color:` +
                        backgroundColor +
                        `">
            <td>` +
                        (index + 1) +
                        `</td>
            <td>` +
                        element.PCode +
                        `</td>
            <td>` +
                        element.Name +
                        `</td>
            <td  class="scrollTd">` +
                        element.peopeladdress +
                        `</td>
            <td>` +
                        element.sabit +
                        `</td>
            <td>` +
                        element.hamrah +
                        `</td>
            <td>` +
                        element.NameRec +
                        `</td>
            <td>2</td>
            <td> <input class="customerList form-check-input" name="customerId" type="radio" value="` +
                        element.PSN +
                        `_` +
                        element.GroupCode +
                        `"></td>
            </tr>`
                );
            });
        },
        error: function (data) {
            alert("bad");
        },
    });
});

$("#searchAlarmName").on("keyup",function(){
    searchTerm=$("#searchAlarmName").val();
    if(!$("#customerWithOutAlarm").is(":checked")){
    $.ajax({
        method: "get",
        url: baseUrl + "/searchAlarms",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm:searchTerm
        },
        async: true,
        success: function (msg) {
            $("#alarmsbody").empty();
            msg.forEach((element,index)=>{
                $("#alarmsbody").append(`<tr onClick="setAlarmCustomerStuff(this,`+element.id+`)">
                                            <td >`+(index+1)+`</td>
                                            <td  style="width:111px">` +
                                            moment(element.TimeStamp, "YYYY/M/D HH:mm:ss")
                                                .locale("fa")
                                                .format("YYYY/M/D") +`</td>
                                            <td>`+element.Name+`</td>
                                            <td>`+element.PhoneStr+`</td>
                                            <td style="width:99px">`+element.countCycle+`</td>
                                            <td style="width:77px">`+element.NameRec+`</td>
                                            <td style="width:66px">`+element.assignedDays+`</td>
                                            <td >`+element.FactDate+`</td>
                                            <td style="width:111px; color:red">`+element.alarmDate+`</td>
                                            <td style="width:166px">`+element.poshtibanName+` `+element.poshtibanLastName+`</td>
                                            <td><input class="customerList form-check-input" name="customerId" type="radio" value="`+element.PSN+`_`+element.adminSn+`_`+element.SerialNoHDS+`"></td>
                                        </tr>`);
            })
        }
        ,error:function(error){

        }
    });
}else{

}
})

function setAlarmCustomerStuff(element) {

    $(element).children("input").prop("checked", true);
    $(".enableBtn").prop("disabled", false);
    if ($(".enableBtn").is(":disabled")) {
    } else {
        $(".enableBtn").css("color", "red !important");
    }
    $(".select-highlight tr").removeClass("selected");
    $(element).toggleClass("selected");
    $("#customerSn").val(
        $(element).children("td").children("input").val().split("_")[0]
    );
    $("#adminSn").val(
        $(element).children("td").children("input").val().split("_")[1]
    );
    $("#factorAlarm").val(
        $(element).children("td").children("input").val().split("_")[2]
    );

    $.ajax({
        method: "get",
        url: baseUrl + "/getAlarmInfo",
        data: {
            _token: "{{ csrf_token() }}",
            factorId: $("#factorAlarm").val()
        },
        async: true
        ,success: function (msg) {
            $("#alarmLastComment").text("");
            $("#alarmLastComment").text(msg.comment);
        }
        ,error:function(error){}
    });
}


$("#searchCustomerAalarmName").on("keyup", () => {
    let searchTerm = $("#searchCustomerAalarmName").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/searchCustomerAalarmName",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm,
        },
        async: true,
        success: function (msg) {
            $("#alarmsbody").empty();
            msg.forEach((element, index) => {
                $("#alarmsbody").append(
                    `<tr onClick="setAlarmCustomerStuff(this)">
            <td>` +
                        (index + 1) +
                        `</td>
                        <td  style="width:111px">` +
                        moment(element.TimeStamp, "YYYY/M/D HH:mm:ss")
                            .locale("fa")
                            .format("YYYY/M/D") +`</td>
            <td>` +
                        element.Name +
                        `</td>
            <td>` +
                        element.peopeladdress +
                        `</td>
            <td style="width:77px">` +
                        element.sabit.trim() +
                        ` ` +
                        element.hamrah.trim() +
                        `</td>
            <td style="width:66px">` +
                        element.NameRec +
                        `</td>
            <td style="width:66px">` +
                        element.assignedDays +
                        `</td>
            <td style="width:166px">` +
                        element.PassedDays +
                        `</td>
            <td>` +
                        element.Name +
                        " " +
                        element.lastName +
                        `</td>
            <td><input class="customerList form-check-input" name="customerId" type="radio" value="` +
                        element.PSN +
                        "_" +
                        element.admin_id +
                        "_" +
                        element.SerialNoHDS +
                        `"></td>
        </tr>`
                );
            });
        },
        error: function (msg) {
            alert("bad");
        },
    });
});

$("#searchCustomerAalarmCode").on("keyup", () => {
    let searchTerm = $("#searchCustomerAalarmCode").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/searchCustomerAalarmCode",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm,
        },
        async: true,
        success: function (msg) {
            $("#alarmsbody").empty();
            msg.forEach((element, index) => {
                $("#alarmsbody").append(
                    `<tr onClick="setAlarmCustomerStuff(this)">
            <td>` +
                        (index + 1) +
                        `</td>
                        <td  style="width:111px">` +
                        moment(element.TimeStamp, "YYYY/M/D HH:mm:ss")
                            .locale("fa")
                            .format("YYYY/M/D") +`</td>
            <td>` +
                        element.Name +
                        `</td>
            <td>` +
                        element.peopeladdress +
                        `</td>
            <td>` +
                        element.sabit.trim() +
                        ` ` +
                        element.hamrah.trim() +
                        `</td>
            <td>` +
                        element.NameRec +
                        `</td>
            <td>` +
                        element.assignedDays +
                        `</td>
            <td>` +
                        element.PassedDays +
                        `</td>
            <td>` +
                        element.Name +
                        " " +
                        element.lastName +
                        `</td>
            <td><input class="customerList form-check-input" name="customerId" type="radio" value="` +
                        element.PSN +
                        "_" +
                        element.admin_id +
                        "_" +
                        element.SerialNoHDS +
                        `"></td>
        </tr>`
                );
            });
        },
        error: function (data) {
            alert("bad");
        },
    });
});

$("#searchCustomerAaramOrder").on("change", () => {
    let searchTerm = $("#searchCustomerAaramOrder").val();
    if (searchTerm > -1) {
        $.ajax({
            method: "get",
            url: baseUrl + "/searchCustomerAalarmOrder",
            data: {
                _token: "{{ csrf_token() }}",
                searchTerm: searchTerm,
            },
            async: true,
            success: function (msg) {
                $("#alarmsbody").empty();
                msg.forEach((element, index) => {
                    $("#alarmsbody").append(
                        `<tr onClick="setAlarmCustomerStuff(this)">
                <td>` +
                            (index + 1) +
                            `</td>
                            <td  style="width:111px">` +
                            moment(element.TimeStamp, "YYYY/M/D HH:mm:ss")
                                .locale("fa")
                                .format("YYYY/M/D") +`</td>
                <td>` +
                            element.Name +
                            `</td>
                <td>` +
                            element.peopeladdress +
                            `</td>
                <td>` +
                            element.sabit.trim() +
                            ` ` +
                            element.hamrah.trim() +
                            `</td>
                <td>` +
                            element.NameRec +
                            `</td>
                <td>` +
                            element.assignedDays +
                            `</td>
                <td>` +
                            element.PassedDays +
                            `</td>
                <td>` +
                            element.Name +
                            " " +
                            element.lastName +
                            `</td>
                <td><input class="customerList form-check-input" name="customerId" type="radio" value="` +
                            element.PSN +
                            "_" +
                            element.admin_id +
                            "_" +
                            element.SerialNoHDS +
                            `"></td>
            </tr>`
                    );
                });
            },
            error: function (data) {
                alert("bad");
            },
        });
    }
});

function calcAndClock() {
    var watch = document.querySelector(".affairs");
    var calc = document.querySelector("#myCalculator");
    if (watch.style.display === "none") {
        watch.style.display = "block";
    } else {
        watch.style.display = "none";
    }

    if (calc.style.display === "block") {
        calc.style.display = "none";
    } else {
        calc.style.display = "block";
    }
}

function clockAndClac() {
    var calculator = document.querySelector(".crmCalculator");
    var clock = document.querySelector("#myWatch");
    if (calculator.style.display === "none") {
        calculator.style.display = "block";
    } else {
        calculator.style.display = "none";
    }

    if (clock.style.display === "block") {
        clock.style.display = "none";
    } else {
        clock.style.display = "block";
    }
}

var cancelButton = $("#cancelComment");
cancelButton.on("click", function () {
    swal({
        title: "اخطار!",
        text: "آیا می خواهید بدون ثبت کامنت خارج شوید؟",
        icon: "warning",
        buttons: true,
    }).then(function (value) {
        if (value === true) {
            $("#addComment").modal("hide");
        } else {
            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#addComment").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });
            $("#addComment").modal("show");
        }
    });
});
$("#firstDateReturned").persianDatepicker({
    cellWidth: 30,
    cellHeight: 12,
    fontSize: 12,
    formatDate: "YYYY/0M/0D",
});
$("#secondDateReturned").persianDatepicker({
    cellWidth: 30,
    cellHeight: 12,
    fontSize: 12,
    formatDate: "YYYY/0M/0D",
    onSelect: () => {
        let secondDate = $("#secondDateReturned").val();
        let firstDate = $("#firstDateReturned").val();

        $.ajax({
            method: "get",
            url: baseUrl + "/searchReturnedByDate",
            data: {
                _token: "{{ csrf_token() }}",
                secondDate: secondDate,
                firstDate: firstDate,
            },
            async: true,
            success: function (msg) {
                moment.locale("en");
                $("#returnedCustomerList").empty();
                msg.forEach((element, index) => {
                    $("#returnedCustomerList").append(
                        `
                    <tr onclick="returnedCustomerStuff(this)">
                        <td>` +
                            (index + 1) +
                            `</td>
                        <td>` +
                            element.Name +
                            `</td>
                        <td>` +
                            element.PCode +
                            `</td>
                        <td>` +
                            element.peopeladdress +
                            `</td>
                        <td>` +
                            element.PhoneStr +
                            `</td>
                        <td>` +
                            element.name +
                            ` ` +
                            element.lastName +
                            `</td>
                        <td>` +
                            moment(element.returnDate, "YYYY-M-D HH:mm:ss")
                                .locale("fa")
                                .format("HH:mm:ss YYYY/M/D") +
                            `</td>
                        <td> <input class="customerList form-check-input" name="customerId[]" type="radio" value="` +
                            element.PSN +
                            `_` +
                            element.adminId +
                            `"></td>
                    </tr> `
                    );
                });
            },
            error: function (data) {
                alert("bad");
            },
        });
    },
});

function selectCustomerLocation(element) {
    $(element).find("input:radio").prop("checked", true);
    let targetRadio = $(element).find("input:radio:checked");
    let customerLoc = targetRadio.val();
    $("#customerLocInputHidden").val(customerLoc);
}

// the following function is used to filter customer with location or without location
$("#customerWithorWithoutLocation").on("change", () => {
    let searchLocation = $("#customerWithorWithoutLocation").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/searchCustomerLocation",
        data: {
            _token: "{{ csrf_token() }}",
            searchLocation: searchLocation,
        },
        async: true,
        success: function (msg) {
            $("#customerLocation").empty();
            msg.forEach((element, index) => {
                $("#customerLocation").append(
                    `
                         <tr id="forTh" onclick="selectCustomerLocation(this);">
                                <td style="width:33px">` +
                        (index + 1) +
                        `</td>
                                <td style="width:250px;">` +
                        element.Name +
                        ` </td>
                                <td style="width:500px;">` +
                        element.peopeladdress +
                        `</td>
                                <td style="width: 90px;">` +
                        element.sabit +
                        `</td>
                                <td style="width: 90px;">` +
                        element.hamrah +
                        `</td>
                            
                                <td style="width:70px;"><span><input type="radio" name="changeLocation" id="selectCustomer" value="` +
                        element.PSN +
                        `"/></span></td>
                          </tr> `
                );
            });
        },
        error: function (data) {},
    });
});

// the following function searching customer by name
$("#searchingCustomerName").on("keyup", () => {
    let searchTerm = $("#searchingCustomerName").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/searchingCustomerName",
        data: {
            _token: "{{ csrf_token() }}",
            searchTerm: searchTerm,
        },
        async: true,
        success: function (msg) {
            $("#customerLocation").empty();
            msg.forEach((element, index) => {
                $("#customerLocation").append(
                    `
                      <tr id="forTh" onclick="selectCustomerLocation(this);">
                            <td style="width:33px">` +
                        (index + 1) +
                        `</td>
                            <td style="width:250px;">` +
                        element.Name +
                        ` </td>
                            <td style="width:500px;">` +
                        element.peopeladdress +
                        `</td>
                            <td style="width: 90px;">` +
                        element.sabit +
                        `</td>
                            <td style="width: 90px;">` +
                        element.hamrah +
                        `</td>
                            <td style="width:70px;"><span><input type="radio" name="changeLocation" id="selectCustomer" value="` +
                        element.PSN +
                        `"/></span></td>
                            
                        </tr>
                     `
                );
            });
        },
        error: function (data) {},
    });
});

function setBargiryStuff(element) {
    $(element).find("input:radio").prop("checked", true);
    let input = $(element).find("input:radio");
    let factorId = input.val().split("_")[1];
    $("#factorId").val(factorId);
    $("#bargiriyBYSId").val(input.val().split("_")[0]);
    $("#totalMoney").text(input.val().split("_")[2] / 10);
    $("#diffPrice1").text(input.val().split("_")[2] / 10);
    $.ajax({
        method: "get",
        url: baseUrl + "/getFactorInfo",
        data: {
            _token: "{{ csrf_token() }}",
            fsn: factorId,
            bargiriyBYSId: $("#bargiriyBYSId").val(),
        },
        async: true,
        success: function (arrayed_result) {
            $facorInfo = arrayed_result[0];
            $bargiryInfo = arrayed_result[1];
            $("#productList").empty();
            $("#customerPhoneFactor").text($facorInfo[0].PhoneStr);
            $("#factorSnFactor").text($facorInfo[0].FactNo);
            $("#customerAddressFactor").text($facorInfo[0].peopeladdress);
            $("#customerNameFactor").text($facorInfo[0].Name);
            $("#factorDate").text($facorInfo[0].FactDate);

            $("#cartPrice1").text($bargiryInfo.KartPrice);
            $("#naghdPrice1").text($bargiryInfo.NaghdPrice);
            $("#takhfifPrice1").text($bargiryInfo.TakhfifPriceBar);
            $("#varizPrice1").text($bargiryInfo.VarizPrice);
            $("#diffPrice1").text($bargiryInfo.DifPrice);
            $("#description1").text($bargiryInfo.DescRec);

            $facorInfo.forEach((element, index) => {
                $("#productList").append(
                    `
                <tr>
                <td class="driveFactor" scope="col">` +
                        (index + 1) +
                        `</td>
                <td scope="col">` +
                        element.GoodName +
                        `</td>
                <td class="driveFactor" scope="col">` +
                        element.Amount +
                        `</td>
                <td scope="col">` +
                        element.UName +
                        `</td>
                <td scope="col">` +
                        (element.Fi / 10).toLocaleString("en-us") +
                        `</td>
                <td style="width:111px;">` +
                        (element.Price / 10).toLocaleString("en-us") +
                        `</td>
                </tr>
        `
                );
            });
        },
        error: function (data) {},
    });
}

$("#openReciveMoneyModal").on("click", function () {
    $("#bargiryFactorId").val($("#bargiriyBYSId").val());
    if (!$(".modal.in").length) {
        $(".modal-dialog").css({
            top: 0,
            left: 0,
        });
    }
    $("#addingDocuments").modal({
        backdrop: false,
        show: true,
    });

    $(".modal-dialog").draggable({
        handle: ".modal-header",
    });

    $("#addingDocuments").modal("show");
    $("#remainHisab").val(parseInt($("#totalMoney").text()));
});

$("#addProvincePhoneCode").on("submit", function (e) {
    $.ajax({
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (data) {
            $("#countryCodeModal").modal("hide");
            $("#PhoneCode").empty();
            data.forEach((element) => {
                $("#PhoneCode").append(
                    `<option value="` +
                        element.provinceCode +
                        `">` +
                        element.provinceCode +
                        `</option>`
                );
            });
        },
        error: function (err) {
            alert("کد اضافه نشد.");
        },
    });
    e.preventDefault();
});

$("#addNewCustomer").on("submit", function (e) {
    e.preventDefault();
    $.ajax({
        method: $(this).attr("method"),
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (data) {
            moment.locale("en");
            $("#addingNewCutomer").modal("hide");
            swal({
                title: "موفق!",
                text: "ثبت شد!",
                icon: "success",
                buttons: true,
            });
            $("#customerListBody1").empty();
            data.forEach((element, index) => {
                $("#customerListBody1").append(
                    `<tr>
                <td class="mobileDisplay" style="width:40px">` +
                        (index + 1) +
                        `</td>
                <td>` +
                        element.Name +
                        `</td>
                <td>` +
                        element.hamrah +
                        `</td>
                <td>` +
                        element.sabit +
                        `</td>
                <td class="mobileDisplay">` +
                        element.NameRec +
                        `</td>
                <td>` +
                        moment(element.TimeStamp, "YYYY/M/D HH:mm:ss")
                            .locale("fa")
                            .format("HH:mm:ss YYYY/M/D") +
                        `</td>
                <td>` +
                        element.peopeladdress +
                        `</td>
                <td class="mobileDisplay">` +
                        element.adminName +
                        ` ` +
                        element.adminLastName +
                        `</td>
                <td style="width:40px"> <input class="customerList form-check-input" name="customerId" type="radio" value="` +
                        element.PSN +
                        ` ` +
                        element.GroupCode +
                        `"></td>
                </tr>`
                );
            });
        },
        error: function (err) {},
    });
});

$("#editCustomerForm").on("submit", function (e) {
    e.preventDefault();
    $.ajax({
        method: $(this).attr("method"),
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (data) {
            moment.locale("en");
            $("#editNewCustomer").modal("hide");
            swal({
                title: "موفق!",
                text: "ثبت شد!",
                icon: "success",
                buttons: true,
            });
            $("#customerListBody1").empty();
            data.forEach((element, index) => {
                $("#customerListBody1").append(
                    `<tr>
                <td class="mobileDisplay" style="width:40px">` +
                        (index + 1) +
                        `</td>
                <td>` +
                        element.Name +
                        `</td>
                <td>` +
                        element.hamrah +
                        `</td>
                <td>` +
                        element.sabit +
                        `</td>
                <td class="mobileDisplay">` +
                        element.NameRec +
                        `</td>
                <td>` +
                        moment(element.TimeStamp, "YYYY/M/D HH:mm:ss")
                            .locale("fa")
                            .format("HH:mm:ss YYYY/M/D") +
                        `</td>
                <td>` +
                        element.peopeladdress +
                        `</td>
                <td class="mobileDisplay">` +
                        element.adminName +
                        ` ` +
                        element.adminLastName +
                        `</td>
                <td style="width:40px"> <input class="customerList form-check-input" name="customerId" type="radio" value="` +
                        element.PSN +
                        ` ` +
                        element.GroupCode +
                        `"></td>
                </tr>`
                );
            });
        },
        error: function (err) {},
    });
});

$("#setReciveMonyDetails").on("submit", function (e) {
    $.ajax({
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (data) {
            $("#addingDocuments").modal("hide");
            swal({
                title: "موفق!",
                text: "ثبت شد!",
                icon: "success",
                buttons: true,
            });
            $("#cartPrice1").text(data.KartPrice);
            $("#naghdPrice1").text(data.NaghdPrice);
            $("#takhfifPrice1").text(data.TakhfifPriceBar);
            $("#varizPrice1").text(data.VarizPrice);
            $("#diffPrice1").text(data.DifPrice);
            $("#description1").text(data.DescRec);
        },
        error: function (err) {
            alert(err);
        },
    });
    e.preventDefault();
});

function showBargiriFactors(element, adminId) {
    $.ajax({
        method: "get",
        url: baseUrl + "/bargeryFactors",
        data: {
            _token: "{{ csrf_token() }}",
            adminId: adminId,
        },
        async: true,
        success: function (arrayed_result) {
            $("#crmDriverBargeri").empty();

            arrayed_result.forEach((element, index) => {
                $("#crmDriverBargeri").append(
                    `
                <tr onclick="setBargiryStuff(this)">
                <td>` + (index + 1) +  `</td>
                <td>` + element.Name + `</td>
                <td class="address">` + element.peopeladdress + `</td>
                <td><a style="color:black; font-size:12px;" href="tel:+900300400"> ` +  element.PhoneStr + ` </a> </td>
                <td style="text-align: center; cursor:pointer; width:111px" data-toggle="modal" data-target="#bargiriFactor"><i class="fa fa-eye fa-1xl"> </i> </td>
                <td class="choice"> <input class="customerList form-check-input" name="factorId" type="radio" value="  ` +
                        element.SnBargiryBYS +  `_` +  element.SerialNoHDS +  `_` + element.TotalPriceHDS + `"></td>
              </tr>
                `
                );
            });
        },
    });
}

function salesExpertSelfInfo(adminId) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getSalesExpertSelfInfo",
        data: {
            _token: "{{ csrf_token() }}",
            adminId: adminId,
        },
        async: true,
        success: function (msg) {
            $("#new_install_today").text(msg[0]);
            $("#all-buys-today").text(msg[1]);
            $("#all-installs").text(msg[2]);
            $("#all_new_buys").text(msg[3]);
            $("#all_monthly_bonuses").text(msg[4]);
        },
        error: function (err) {
            alert("error in getting salesExpertSalesInfo");
        },
    });
}

function salesExpertSelfInfo(adminId) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getSalesExpertSelfInfo",
        data: {
            _token: "{{ csrf_token() }}",
            adminId: adminId,
        },
        async: true,
        success: function (msg) {
            $("#new_install_today").text(msg[0]);
            $("#all-buys-today").text(msg[1]);
            $("#all-installs").text(msg[2]);
            $("#all_new_buys").text(msg[3]);
        },
        error: function (err) {
            alert("error in getting salesExpertSalesInfo");
        },
    });
}

function setAmalkardStuff(element,customerId) {
    $("tr").removeClass("selected");
    $(element).toggleClass("selected");
    $(".enableBtn").val(customerId);
    $("#customerSnLogin").val(customerId);
    $(".enableBtn").prop("disabled",false);
}
function openDashboard(customerId) {
    let csn = customerId;
    $("#customerSnLogin").val(customerId);
    if ($("#customerSn")) {
        $("#customerSn").val(csn);
    }
    $.ajax({
        method: "get",
        url: baseUrl + "/customerDashboard",
        dataType: "json",
        contentType: "json",
        data: {
            _token: "{{ csrf_token() }}",
            csn: csn,
        },
        async: true,
        success: function (msg) {
            console.log(msg)
            moment.locale("en");
            let exactCustomer = msg[0];
            let factors = msg[1];
            let goodDetails = msg[2];
            let basketOrders = msg[3];
            let comments = msg[4];
            let specialComments = msg[5];
            let specialComment = specialComments[0];
            let assesments = msg[6];
            let returnedFactors = msg[7];
            let loginInfo = msg[8];
            $("#customerProperty").val("");
            if (specialComment) {
                $("#customerProperty").val(specialComment.comment.trim());
            }
            $("#dashboardTitle").text(exactCustomer.Name);
            $("#customerCode").val(exactCustomer.PCode);
            $("#customerName").val(exactCustomer.Name);
            $("#customerAddress").val(exactCustomer.peopeladdress);
            $("#username").val(exactCustomer.userName);
            $("#password").val(exactCustomer.customerPss);
            $("#mobile1").val(exactCustomer.PhoneStr);
            $("#customerIdForComment").val(exactCustomer.PSN);
            $("#countFactor").val(exactCustomer.countFactor);
            $("#factorTable").empty();
            factors.forEach((element, index) => {
                $("#factorTable").append(
                    `<tr class="tbodyTr">
                <td>` +
                        (index + 1) +
                        `</td>
                <td>` +
                        element.FactDate +
                        `</td>
                <td>نامعلوم</td>
                <td>` +
                        parseInt(element.TotalPriceHDS / 10).toLocaleString(
                            "en-us"
                        ) +
                        `</td>
                <td onclick="showFactorDetails(this)"><input name="factorId" style="display:none"  type="radio" value="` +
                        element.SerialNoHDS +
                        `" /><i class="fa fa-eye" /></td>
            </tr>`
                );
            });

            $("#returnedFactorsBody").empty();
            returnedFactors.forEach((element, index) => {
                $("#returnedFactorsBody").append(
                    `<tr class="tbodyTr">
            <td>` +
                        (index + 1) +
                        `</td>
            <td>` +
                        element.FactDate +
                        `</td>
            <td>نامعلوم</td>
            <td>` +
                        parseInt(element.TotalPriceHDS / 10).toLocaleString(
                            "en-us"
                        ) +
                        `</td>
            </tr>`
                );
            });
            $("#goodDetail").empty();
            goodDetails.forEach((element, index) => {
                $("#goodDetail").append(
                    `
            <tr class="tbodyTr">
                <td>` +
                        (index + 1) +
                        ` </td>
                <td>` +
                        moment(element.maxTime, "YYYY/M/D HH:mm:ss")
                            .locale("fa")
                            .format("YYYY/M/D") +
                        `</td>
                <td>` +
                        element.GoodName +
                        `</td>
                <td>  </td>
                <td>  </td>
                
            </tr>`
                );
            });

            $("#basketOrders").empty();
            basketOrders.forEach((element, index) => {
                $("#basketOrders").append(
                    `<tr>
                <td>` +
                        (index + 1) +
                        `</td>
                <td>` +
                        moment(element.TimeStamp, "YYYY/M/D HH:mm:ss")
                            .locale("fa")
                            .format("YYYY/M/D") +
                        `</td>
                <td>` +
                        element.GoodName +
                        `</td>
                <td>` +
                        element.Amount +
                        `</td>
                <td>` +
                        element.Fi +
                        `</td>
                </tr>`
                );
            });

            $("#customerLoginInfoBody").empty();
            if (loginInfo) {
                loginInfo.forEach((element, index) => {
                    $("#customerLoginInfoBody").append(
                        `<tr>
                            <td>` + (index + 1) + `</td>
                            <td>` + moment(element.visitDate, "YYYY/M/D HH:mm:ss").locale("fa").format("YYYY/M/D") +`</td>
                            <td>` +element.platform +`</td>
                            <td style="width:100px;">` +element.browser +`</td>
                </tr>`
                    );
                });
            }

            $("#customerComments").empty();
            comments.forEach((element, index) => {
                $("#customerComments").append(
                    `<tr class="tbodyTr">
                <td> ` +  (index + 1) +  ` </td>
                <td>` +  moment(element.TimeStamp, "YYYY/M/D HH:mm:ss") .locale("fa") .format("YYYY/M/D") + `</td>
                <td onclick="viewComment(` + element.id + `)"</td>` +  element.newComment.substr(0, 10) +  `... <i class="fas fa-comment-dots float-end"></i> </td>
                <td onclick="viewNextComment(` +element.id + `)">` +  element.nexComment.substr(0, 10) +  `... <i class="fas fa-comment-dots float-end"></i>  </td>
                <td style="width:111px !important;">` +   moment(element.specifiedDate, "YYYY/M/D")
                            .locale("fa")
                            .format("YYYY/M/D") +
                        `</td>
                </tr>`
                );
            });
            $("#customerAssesments").empty();
            assesments.forEach((element, index) => {
                let driverBehavior = "";
                let shipmentProblem = "بله";
                if (element.shipmentProblem == 1) {
                    shipmentProblem = "خیر";
                }
                switch (parseInt(element.driverBehavior)) {
                    case 1:
                        driverBehavior = "عالی";
                        break;
                    case 2:
                        driverBehavior = "خوب";
                        break;
                    case 3:
                        driverBehavior = "متوسط";
                        break;
                    case 4:
                        driverBehavior = "بد";
                        break;
                    default:
                        break;
                }
                $("#customerAssesments").append(
                    `
            <tr>
            <td>` + (index + 1) +`</td>
            <td>` + moment(element.TimeStamp, "YYYY/M/D").locale("fa").format("YYYY/M/D") +`</td>
            <td>` +element.comment + `</td>
            <td>` + driverBehavior + `</td>
            <td class="scrollTd">` +shipmentProblem +`</td>
            <td style="width:100px"></td>
        </tr>`
                );
            });
            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#customerDashboard").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });
            $("#customerDashboard").modal("show");
        },
        error: function (data) {},
    });
}

function getTodaySelfInstalls(adminId, bounus, limitAmount) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getTodaySelfInstalls",
        data: {
            _token: "{{ csrf_token() }}",
            adminId: adminId,
        },
        async: true,
        success: function (arrayed_result) {
            let countAllTodayInstall = arrayed_result.length;
            if (!countAllTodayInstall) {
            }
            let allBonusToday =
                parseInt(countAllTodayInstall / limitAmount) * bounus;
            $("#all_today_bonus").text(allBonusToday);
            $("#new_customer_today_div").empty();
            arrayed_result.forEach((element, index) => {
                let groupName = "";
                if (element.GroupName) {
                    groupName = element.GroupName;
                } else {
                    groupName = "نا مشخص";
                }
                $("#new_customer_today_div").append(
                    `
                    <div class="row mb-2">
                        <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button"><a href="">` +
                        element.Name +
                        `</a> </button> </div>
                        <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button"><a href="tel:` +
                        element.PhoneStr.split("-")[0] +
                        `"> ` +
                        element.PhoneStr.split("-")[0] +
                        ` </a> </button> </div>
<div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button"><a href="tel:` +
                        element.PhoneStr.split("-")[0] +
                        `"> ` +
                        groupName +
                        ` </a> </button> </div>
                        <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button" onclick="openDashboard(` +
                        element.PSN +
                        `)"><a href="#"> ` +
                        moment(element.addedDate, "YYYY/M/D h:mm:s")
                            .locale("fa")
                            .format("YYYY/M/D h:mm:s") +
                        `</a> </button> </div>
                    </div>`
                );
            });
        },
        error: {},
    });
}

function getAllNewInstallSelf(adminId, bonus, limitAmount) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getAllNewInstallSelf",
        data: {
            _token: "{{ csrf_token() }}",
            adminId: adminId,
        },
        async: true,
        success: function (arrayed_result) {
            moment.locale("en");
            let countAllInstalls = arrayed_result.length;
            let allBonus = parseInt(countAllInstalls / limitAmount) * bonus;
            if (!allBonus) {
                allBonus = 0;
            }
            $("#allInstallBonus").text(allBonus);
            $("#all_new_install").empty();

            arrayed_result.forEach((element, index) => {
                let groupName = "";
                if (element.GroupName) {
                    groupName = element.GroupName;
                } else {
                    groupName = "نامشخص";
                }
                $("#all_new_install").append(
                    `  <div class="row mb-2"> 
                <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button"> <a href="">` +
                        element.Name +
                        `</a> </button> </div>
                <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button"><a href="tel:` +
                        element.PhoneStr.split("-")[0] +
                        `"> ` +
                        element.PhoneStr.split("-")[0] +
                        ` </a> </button> </div>
<div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button"><a href="tel:` +
                        element.PhoneStr.split("-")[0] +
                        `"> ` +
                        groupName +
                        ` </a> </button> </div>
                <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button" onclick="openDashboard(` +
                        element.PSN +
                        `)"><a href="#"> ` +
                        moment(element.addedTime, "YYYY/M/D HH:mm:ss")
                            .locale("fa")
                            .format("YYYY/M/D HH:mm:ss") +
                        `</a> </button> </div>
                </div>`
                );
            });
        },
        error: function () {
            alert("all installs data is not correct!");
        },
    });
}
function getAllBuyAghlamSelf(adminId, emptydate, limitAmount) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getAllBuyAghlamSelf",
        data: {
            _token: "{{ csrf_token() }}",
            adminId: adminId,
            emptydate: `` + emptydate + ``,
        },
        async: true,
        success: function (arrayed_result) {
            $("#all_aghlam_list").empty();
            arrayed_result.forEach((element, index) => {
                $("#all_aghlam_list").append(
                    `
            <div class="row mb-2"> 
                <div class="col-4 col-sm-4 px-1">  <button class="btn btn-info btn-sm nasb-button"><a href="#"> ` +
                        (index + 1) +
                        `</a></button> </div>
                <div class="col-4 col-sm-4 px-1">  <button class="btn btn-info btn-sm nasb-button"> <a href="">` +
                        element.GoodName +
                        `</a> </button> </div>
                <div class="col-4 col-sm-4 px-1">  <button class="btn btn-info btn-sm nasb-button" onclick="openKalaDashboard(` +
                        element.GoodSn +
                        `)"><a href="#">  داشبورد خرید</a> </button> </div>
            </div>
            `
                );
            });
        },
        error: function (error) {
            alert("aghlam not found error");
        },
    });
}

function getAllBuyAghlamPoshtiban(adminId, emptyDate, limitAmount) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getAllBuyAghlamByAdmin",
        data: {
            _token: "{{ csrf_token() }}",
            adminId: adminId,
            emptydate: `` + emptyDate + ``,
        },
        async: true,
        success: function (arrayed_result) {
            console.log(arrayed_result);
            $("#all_aghlam_list").empty();
            arrayed_result.forEach((element, index) => {
                $("#all_aghlam_list").append(
                    `
            <div class="row mb-2"> 
                <div class="col-4 col-sm-4 px-1">  <button class="btn btn-info btn-sm nasb-button"><a href="#"> ` +
                        (index + 1) +
                        `</a></button> </div>
                <div class="col-4 col-sm-4 px-1">  <button class="btn btn-info btn-sm nasb-button"> <a href="">` +
                        element.GoodName +
                        `</a> </button> </div>
                <div class="col-4 col-sm-4 px-1">  <button class="btn btn-info btn-sm nasb-button" onclick="openKalaDashboard(` +
                        element.GoodSn +
                        `)"><a href="#">  داشبورد خرید</a> </button> </div>
            </div>
            `
                );
            });
        },
    });
}

function getTodayBuyAghlamSelf(adminId) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getTodayBuyAghlamSelf",
        data: {
            _token: "{{ csrf_token() }}",
            adminId: adminId,
        },
        async: true,
        success: function (arrayed_result) {
            $("#today_aghlam_list").empty();
            arrayed_result.forEach((element, index) => {
                $("#today_aghlam_list").append(
                    `
            <div class="row mb-2"> 
<div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button"><a href="#">  ` +
                        (index + 1) +
                        `</a></button> </div>
                <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button"> <a href="">` +
                        element.GoodName +
                        `</a> </button> </div>
                
                <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button" onclick="openKalaDashboard(` +
                        element.GoodSn +
                        `)"><a href="#">  داشبورد خرید</a> </button> </div>
            </div>
            `
                );
            });
        },
        error: function (error) {
            alert("aghlam not found error");
        },
    });
}
function getAllBuyMoneySelf(adminId, bonus, limitAmount) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getAllBuyMoneySelf",
        data: {
            _token: "{{ csrf_token() }}",
            adminId: adminId,
        },
        async: true,
        success: function (arrayed_result) {
            $("#all_mablagh_list").empty();
            arrayed_result.forEach((element, index) => {
                $("#all_mablagh_list").append(
                    `
            <div class="row mb-2"> 
                <div class="col-4 col-sm-4 px-1">  <button class="btn btn-info btn-sm nasb-button"> <a href="">` +
                        element.Name +
                        `</a> </button> </div>
                <div class="col-4 col-sm-4 px-1">  <button class="btn btn-info btn-sm nasb-button"><a href="#"> ` +
                        parseInt(element.SumOfMoney / 10).toLocaleString(
                            "us-en"
                        ) +
                        `</a></button> </div>
                <div class="col-4 col-sm-4 px-1">  <button class="btn btn-info btn-sm nasb-button" onclick="openDashboard(` +
                        element.PSN +
                        `)"><a href="#">داشبورد</a> </button> </div>
            </div>
            `
                );
            });
        },
        error: function (error) {},
    });
}

function getAllBuyMoneyPoshtiban(adminId, emptyDate, limitAmount) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getAllBuyMoneyPoshtiban",
        data: {
            _token: "{{ csrf_token() }}",
            adminId: adminId,
            adminDate: `` + emptyDate + ``,
        },
        async: true,
        success: function (arrayed_result) {
            $("#all_mablagh_list").empty();
            arrayed_result.forEach((element, index) => {
                $("#all_mablagh_list").append(
                    `
            <div class="row mb-2"> 
                <div class="col-4 col-sm-4 px-1">  <button class="btn btn-info btn-sm nasb-button"> <a href="">` +
                        element.Name +
                        `</a> </button> </div>
                <div class="col-4 col-sm-4 px-1">  <button class="btn btn-info btn-sm nasb-button"><a href="#"> ` +
                        parseInt(element.SumOfMoney / 10).toLocaleString(
                            "en-us"
                        ) +
                        `</a></button> </div>
                <div class="col-4 col-sm-4 px-1">  <button class="btn btn-info btn-sm nasb-button" onclick="openDashboard(` +
                        element.PSN +
                        `)"><a href="#">داشبورد</a> </button> </div>
            </div>
            `
                );
            });
        },
        error: function (error) {},
    });
}

function getAllNewBuyPoshtiban(adminId, emptyDate, limitAmount) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getAllNewBuyPoshtiban",
        data: {
            _token: "{{ csrf_token() }}",
            adminId: adminId,
            emptyDate: `` + emptyDate + ``,
        },
        async: true,
        success: function (arrayed_result) {
            $("#all_new_buys_list").empty();
            arrayed_result.forEach((element, index) => {
                $("#all_new_buys_list").append(
                    `
            <div class="row mb-2"> 
                <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button"> <a href="">` +
                        element.Name +
                        `</a> </button> </div>
                <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button"><a href="#"> ` +
                        parseInt(element.SumOfMoney / 10).toLocaleString(
                            "en-us"
                        ) +
                        `</a></button> </div>
                <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button" onclick="openDashboard(` +
                        element.PSN +
                        `)"><a href="#">داشبورد</a> </button> </div>
            </div>
            `
                );
            });
        },
        error: function (error) {},
    });
}
function getTodayPoshtibanBuy(adminId) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getAllNewTodayBuyPoshtiban",
        data: {
            _token: "{{ csrf_token() }}",
            adminId: adminId,
        },
        async: true,
        success: function (arrayed_result) {
            console.log(arrayed_result);
            $("#new_buy_today_div").empty();
            arrayed_result.forEach((element, index) => {
                $("#new_buy_today_div").append(
                    `
            <div class="row mb-2"> 
                <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button"> <a href="">` +
                        element.Name +
                        `</a> </button> </div>
                <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button"><a href="#"> ` +
                        parseInt(element.totalMoney / 10).toLocaleString(
                            "en-us"
                        ) +
                        `</a></button> </div>
                <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button" onclick="openDashboard(` +
                        element.PSN +
                        `)"><a href="#">داشبورد</a> </button> </div>
            </div>
            `
                );
            });
        },
        error: function (error) {},
    });
}

function getTodayBuyMoneySelf(adminId, bonus, limitAmount) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getTodayBuyMoneySelf",
        data: {
            _token: "{{ csrf_token() }}",
            adminId: adminId,
        },
        async: true,
        success: function (arrayed_result) {
            $("#today_mablagh_list").empty();
            arrayed_result.forEach((element, index) => {
                $("#today_mablagh_list").append(
                    `
            <div class="row mb-2"> 
                <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button"> <a href="">` +
                        element.Name +
                        `</a> </button> </div>
                <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button"><a href="#"> ` +
                        element.SumOfMoney +
                        `</a></button> </div>
                <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button" onclick="openDashboard(` +
                        element.PSN +
                        `)"><a href="#">داشبورد</a> </button> </div>
            </div>
            `
                );
            });
        },
        error: function (error) {},
    });
}

function getAllBuyMoneyTodayPoshtiban(adminId) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getAllBuyMoneyTodayPoshtiban",
        data: {
            _token: "{{ csrf_token() }}",
            adminId: adminId,
        },
        async: true,
        success: function (arrayed_result) {
            $("#today_mablagh_list").empty();
            arrayed_result.forEach((element, index) => {
                $("#today_mablagh_list").append(
                    `
            <div class="row mb-2"> 
                <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button"> <a href="">` +
                        element.Name +
                        `</a> </button> </div>
                <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button"><a href="#"> ` +
                        parseInt(element.SumOfMoney / 10).toLocaleString(
                            "en-us"
                        ) +
                        `</a></button> </div>
                <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button" onclick="openDashboard(` +
                        element.PSN +
                        `)"><a href="#">داشبورد</a> </button> </div>
            </div>
            `
                );
            });
        },
        error: function (error) {
            alert("server side error data");
        },
    });
}

function getTodaySelfBuyToday(adminId, emptyDate, limitAmount) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getTodaySelfBuyToday",
        data: {
            _token: "{{ csrf_token() }}",
            adminId: adminId,
        },
        async: true,
        success: function (arrayed_result) {
            $("#new_buy_today_div").empty();
            arrayed_result.forEach((element, index) => {
                $("#new_buy_today_div").append(
                    `
                                    <div class="row mb-2"> 
                                    <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button"> <a href="">` +
                        element.Name +
                        `</a> </button> </div>
                                    <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button"><a href="tel:` +
                        element.PhoneStr.split("-")[0] +
                        `"> ` +
                        element.PhoneStr.split("-")[0] +
                        ` </a> </button> </div>
                                    <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button" onclick="openDashboard(` +
                        element.PSN +
                        `)"><a href="#"> داشبورد </a> </button> </div>
                                </div>`
                );
            });
        },
        error: function (error) {
            alert("error in self buy today data");
        },
    });
}

function getAllNewBuySelf(adminId, emptyDate, limitAmount) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getAllNewBuySelf",
        data: {
            _token: "{{ csrf_token() }}",
            adminId: adminId,
            emptyDate: emptyDate,
        },
        async: true,
        success: function (arrayed_result) {
            $("#all_new_buys_div").empty();
            arrayed_result.forEach((element, index) => {
                $("#all_new_buys_div").append(
                    ` <div class="row mb-2"> 
                                                <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button"> <a href="">` +
                        element.Name +
                        `</a> </button> </div>
 <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button"><a href="tel:` +
                        element.PhoneStr.split("-")[0] +
                        `"> ` +
                        element.PhoneStr.split("-")[0] +
                        ` </a> </button> </div>
                                                <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button" onclick="openDashboard(` +
                        element.PSN +
                        `)"><a href="#"> داشبورد </a> </button> </div>
                                                </div>`
                );
            });
        },
        error: function () {
            alert("an error has occured on getting data of all new buys!");
        },
    });
}

$("#firstDateSefSaleExpert").persianDatepicker({
    cellWidth: 32,
    cellHeight: 22,
    fontSize: 14,
    formatDate: "YYYY/0M/0D",
});

$("#secondDateSefSaleExpert").persianDatepicker({
    cellWidth: 32,
    cellHeight: 22,
    fontSize: 14,
    formatDate: "YYYY/0M/0D",
    onSelect: () => {
        const adminId = $("#adminId").val();
        const firstDate = $("#secondDateSefSaleExpert").val();
        const secondDate = $("#firstDateSefSaleExpert").val();
        $.ajax({
            method: "get",
            url: baseUrl + "/getSalesExpertSelfInfoByDates",
            data: {
                _token: "{{ csrf_token() }}",
                adminId: adminId,
                firstDate: $("#firstDateSefSaleExpert").val(),
                secondDate: $("#secondDateSefSaleExpert").val(),
            },
            async: true,
            success: function (msg) {
                $("#all-installs").text(msg[0]);
                $("#all_new_buys").text(msg[1]);
            },
            error: function (err) {
                alert("error in getting salesExpertSalesInfo");
            },
        });
    },
});

// appending the related employee to managers table

function getBossBazarYab(bossId, iteration) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getBossBazarYab",
        data: {
            _token: "{{ csrf_token() }}",
            bossId: bossId,
        },
        async: true,
        success: function (arrayed_result) {
            $("#fellowEmployee" + iteration).empty();
            arrayed_result.forEach((element, index) => {
                $("#fellowEmployee" + iteration).append(
                    `
                    <tr onclick="setSubBazaryabStuff(this)">
                        <td style="width:88px">` +
                        (index + 1) +
                        `</td>
                        <td style="width:140px">` +
                        element.name +
                        ` ` +
                        element.lastName +
                        `</td>
                        <td style="width:60px">
                            <input type="radio" name="adminId" value="` +
                        element.id +
                        `">
                        </td>
                    </tr>
                `
                );
            });
        },

        error: function () {
            alert("an error has occured on getting data of all salesExpert!");
        },
    });
}

$("#addTarget").on("submit", function (e) {
    alert("success");
    $.ajax({
        method: $(this).attr("method"),
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (data) {
            $("#targetList").empty();
            data.forEach((element, index) => {
                $("#targetList").append(
                    `<tr  onclick="setTargetStuff(this)">
                <td>` +
                        (index + 1) +
                        `</td><td>` +
                        element.BaseName +
                        `</td>
                <td>` +
                        element.firstTarget +
                        `</td><td>` +
                        element.firstTargetBonus +
                        `</td>
                <td>` +
                        element.secondTarget +
                        `</td><td>` +
                        element.secondTargetBonus +
                        `</td>
                <td>` +
                        element.thirdTarget +
                        `</td><td>` +
                        element.thirdTargetBonus +
                        `</td>
                <td><input class="form-check-input" name="targetId" type="radio" value="` +
                        element.id +
                        `"></td>
                </tr>`
                );
            });
        },
        error: function (error) {},
    });
    e.preventDefault();
});

$("#selectTarget").on("change", function () {
    const targetId = $(this).val();
    $.ajax({
        method: "get",
        url: baseUrl + "/getTargetInfo",
        data: {
            _token: "{{ csrf_token() }}",
            targetId: targetId,
        },
        async: true,
        success: function (data) {
            $("#targetList").empty();
            data.forEach((element, index) => {
                $("#targetList").append(
                    `<tr  onclick="setTargetStuff(this)">
                <td>` +
                        (index + 1) +
                        `</td><td>` +
                        element.BaseName +
                        `</td>
                <td>` +
                        element.firstTarget +
                        `</td><td>` +
                        element.firstTargetBonus +
                        `</td>
                <td>` +
                        element.secondTarget +
                        `</td><td>` +
                        element.secondTargetBonus +
                        `</td>
                <td>` +
                        element.thirdTarget +
                        `</td><td>` +
                        element.thirdTargetBonus +
                        `</td>
                <td><input class="form-check-input" name="targetId" type="radio" value="` +
                        element.id +
                        `"></td>
                </tr>`
                );
            });
        },
        error: function () {
            alert("cant get data of target!!");
        },
    });
});

$("#editTarget").on("submit", function (e) {
    $("#editingTargetModal").modal("hide");
    $.ajax({
        method: $(this).attr("method"),
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (data) {
            $("#targetList").empty();
            data.forEach((element, index) => {
                $("#targetList").append(
                    `<tr  onclick="setTargetStuff(this)">
                <td>` +
                        (index + 1) +
                        `</td><td>` +
                        element.BaseName +
                        `</td>
                <td>` +
                        parseInt(element.firstTarget).toLocaleString("en-US") +
                        `</td><td>` +
                        element.firstTargetBonus +
                        `</td>
                <td>` +
                        parseInt(element.secondTarget).toLocaleString("en-US") +
                        `</td><td>` +
                        element.secondTargetBonus +
                        `</td>
                <td>` +
                        parseInt(element.thirdTarget).toLocaleString("en-US") +
                        `</td><td>` +
                        element.thirdTargetBonus +
                        `</td>
                <td><input class="form-check-input" name="targetId" type="radio" value="` +
                        element.id +
                        `"></td>
                </tr>`
                );
            });
        },
        error: function (error) {},
    });
    e.preventDefault();
});

function setTargetStuff(element) {
    $(element).find("input:radio").prop("checked", true);
    let input = $(element).find("input:radio");
    const targetId = input.val();
    $("#selectTargetId").val(targetId);
    $("#deleteTargetBtn").prop("disabled", false);
}

$("#selectTarget").on("change", () => {
    $(".targetTable").css("display", "block");
});

$(".targetTableTr").on("click", () => {
    $("#targetEditBtn").prop("disabled", false);
});

function setSpecialBonusStuff(element) {
    $(element).find("input:radio").prop("checked", true);
    const input = $(element).find("input:radio");
    const bonusId = input.val();
    $("#specialBonusIdForEdit").val(bonusId);
    $("#specialBonusBtn").prop("disabled", false);
    $("#deleteSpecialBonus").prop("disabled", false);
}

$("#targetEditBtn").on("click", function () {
    const targetId = $("#selectTargetId").val();
    $("#targetIdForEdit").val(targetId);
    $.ajax({
        method: "get",
        url: baseUrl + "/getTargetInfo",
        data: {
            _token: "{{ csrf_token() }}",
            targetId: targetId,
        },
        async: true,
        success: function (data) {
            msg = data[0];
            $("#baseName").val(msg.BaseName);
            $("#firstTarget").val(
                parseInt(msg.firstTarget).toLocaleString("en-US")
            );
            $("#firstTargetBonus").val(msg.firstTargetBonus);
            $("#secondTarget").val(
                parseInt(msg.secondTarget).toLocaleString("en-US")
            );
            $("#secondTargetBonus").val(msg.secondTargetBonus);
            $("#thirdTarget").val(
                parseInt(msg.thirdTarget).toLocaleString("en-US")
            );
            $("#thirdTargetBonus").val(msg.thirdTargetBonus);
            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#editingTargetModal").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });
            $("#editingTargetModal").modal("show");
        },
        error: function () {
            alert("cant get data of target!!");
        },
    });
});

$("#specialBonusBtn").on("click", function () {
    const specialBonusId = $("#specialBonusIdForEdit").val();

    $.ajax({
        method: "get",
        url: baseUrl + "/getSpecialBonusInfo",
        data: {
            _token: "{{ csrf_token() }}",
            bonusId: specialBonusId,
        },
        async: true,
        success: function (msg) {
            let data = msg[0];
            $("#specialBaseName").val(data.BaseName);
            $("#specialBonus").val(data.Bonus);
            $("#limitAmount").val(
                parseInt(data.limitAmount).toLocaleString("en-US")
            );
            $("#specialBaseId").val(data.id);
            if (data.id == 13) {
                $("#limitDiv").text("(تومان)");
            } else {
                $("#limitDiv").text("(تعداد)");
            }
            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#editSpecialBonusModal").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });
            $("#editSpecialBonusModal").modal("show");
        },
        error: function () {},
    });
});

$("#editBonusForm").on("submit", function (e) {
    $.ajax({
        method: $(this).attr("method"),
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (data) {
            $("#specialBonusList").empty();
            data.forEach((element, index) => {
                $("#specialBonusList").append(
                    `
                <tr  onclick="setSpecialBonusStuff(this)">
                <td  style="width:100px;">` +
                        (index + 1) +
                        `</td>
                <td>` +
                        element.BaseName +
                        `</td>
                <td>` +
                        element.Bonus +
                        `</td>
                <td>` +
                        element.limitAmount +
                        `</td>
                <td> <input class="form-check-input" name="specialBonusId" type="radio" value="` +
                        element.id +
                        `"></td>
                </tr>
                `
                );
            });
        },
        error: function () {
            alert("update is not completed");
        },
    });
    e.preventDefault();
});

$("#deleteTargetBtn").on("click", function () {
    $.ajax({
        method: "get",
        url: baseUrl + "/deleteTarget",
        data: {
            _token: "{{ csrf_token() }}",
            baseId: $("#selectTargetId").val(),
        },
        async: true,
        success: function (data) {
            $("#targetList").empty();
            data.forEach((element, index) => {
                $("#targetList").append(
                    `<tr  onclick="setTargetStuff(this)">
        <td>` +
                        (index + 1) +
                        `</td><td>` +
                        element.BaseName +
                        `</td>
        <td>` +
                        element.firstTarget +
                        `</td><td>` +
                        element.firstTargetBonus +
                        `</td>
        <td>` +
                        element.secondTarget +
                        `</td><td>` +
                        element.secondTargetBonus +
                        `</td>
        <td>` +
                        element.thirdTarget +
                        `</td><td>` +
                        element.thirdTargetBonus +
                        `</td>
        <td><input class="form-check-input" name="targetId" type="radio" value="` +
                        element.id +
                        `"></td>
        </tr>`
                );
            });
        },
    });
});

$("#deleteSpecialBonus").on("click", function () {
    $.ajax({
        method: "get",
        url: baseUrl + "/deleteSpecialBonus",
        data: {
            _token: "{{ csrf_token() }}",
            baseId: $("#specialBonusIdForEdit").val(),
        },
        async: true,
        success: function (data) {
            $("#specialBonusList").empty();
            data.forEach((element, index) => {
                $("#specialBonusList").append(
                    `
                    <tr  onclick="setSpecialBonusStuff(this)">
                    <td  style="width:100px;">` +
                        (index + 1) +
                        `</td>
                    <td>` +
                        element.BaseName +
                        `</td>
                    <td>` +
                        element.Bonus +
                        `</td>
                    <td>` +
                        element.limitAmount +
                        `</td>
                    <td> <input class="form-check-input" name="specialBonusId" type="radio" value="` +
                        element.id +
                        `"></td>
                    </tr>
                    `
                );
            });
        },
        error: function (err) {
            alert("cant delete any special Bonus");
        },
    });
});

$("#addBonusForm").on("submit", function (e) {
    $.ajax({
        method: $(this).attr("method"),
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (data) {
            $("#specialBonusList").empty();
            data.forEach((element, index) => {
                $("#specialBonusList").append(
                    `
                <tr  onclick="setSpecialBonusStuff(this)">
                <td  style="width:100px;">` +
                        (index + 1) +
                        `</td>
                <td>` +
                        element.BaseName +
                        `</td>
                <td>` +
                        element.Bonus +
                        `</td>
                <td>` +
                        element.limitAmount +
                        `</td>
                <td> <input class="form-check-input" name="specialBonusId" type="radio" value="` +
                        element.id +
                        `"></td>
                </tr>
                `
                );
            });
        },
        error: function (error) {},
    });
    e.preventDefault();
});

function setSubBazaryabStuff(element) {
    $(element).find("input:radio").prop("checked", true);
    const input = $(element).find("input:radio");
    const subBazaryabId = input.val();
    $("#subBazaryabId").val(subBazaryabId);
    alert(subBazaryabId)
    $("#PoshtibanId").val(subBazaryabId);
    $("#subListDashboardBtn").prop("disabled", false);
}

function setSubPoshtibanStuff(element) {
    $(element).find("input:radio").prop("checked", true);
    const input = $(element).find("input:radio");
    const subBazaryabId = input.val();
    $("#subPoshtibanId").val(subBazaryabId);
    $("#subListDashboardBtn").prop("disabled", false);
}

function openHistoryModal() {
    if (!$(".modal.in").length) {
        $(".modal-dialog").css({
            top: 0,
            left: 0,
        });
    }
    $("#selfHistoryModal").modal({
        backdrop: false,
        show: true,
    });

    $(".modal-dialog").draggable({
        handle: ".modal-header",
    });
    $("#selfHistoryModal").modal("show");
}

$("#limitAmount").on("keyup", () => {
    if (!$("#limitAmount").val()) {
        $("#limitAmount").val(0);
    }

    $("#limitAmount").val(
        parseInt($("#limitAmount").val().replace(/\,/g, "")).toLocaleString(
            "en-US"
        )
    );
});
$("#firstTarget").on("keyup", () => {
    if (!$("#firstTarget").val()) {
        $("#firstTarget").val(0);
    }

    $("#firstTarget").val(
        parseInt($("#firstTarget").val().replace(/\,/g, "")).toLocaleString(
            "en-US"
        )
    );
});
$("#secondTarget").on("keyup", () => {
    if (!$("#secondTarget").val()) {
        $("#secondTarget").val(0);
    }

    $("#secondTarget").val(
        parseInt($("#secondTarget").val().replace(/\,/g, "")).toLocaleString(
            "en-US"
        )
    );
});
$("#thirdTarget").on("keyup", () => {
    if (!$("#thirdTarget").val()) {
        $("#thirdTarget").val(0);
    }

    $("#thirdTarget").val(
        parseInt($("#thirdTarget").val().replace(/\,/g, "")).toLocaleString(
            "en-US"
        )
    );
});

function setEditRTStuff(csn) {
    $("#editRTbtn").val(csn);
}

$("#editRTbtn").on("click", function () {
    let customerId = $("#editRTbtn").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/getRandTInfo",
        data: {
            _token: "{{ csrf_token() }}",
            csn: customerId,
        },
        async: true,
        success: function (respond) {
            let exactCustomerInfo = respond[0];
            let phones = respond[1];
            let cities = respond[2];
            let mantagheh = respond[3];

            $("#customerID").val(exactCustomerInfo.PSN);
            $("#name").val(exactCustomerInfo.Name);
            $("#PCode").val(exactCustomerInfo.PCode);
            $("#mobilePhone").val(phones[0].hamrah);
            $("#sabitPhone").val(phones[0].sabit);
            alert(exactCustomerInfo.Description);
            $("#discription").val(exactCustomerInfo.Description);
            $("#gender").empty();
            $("#gender").append(`
                <option value="2" >مرد</option>
                <option value="1" >زن</option>`);
            $("#snNahiyehE").empty();
            cities.forEach((element, index) => {
                let selectRec = "";
                if (element.SnMNM == exactCustomerInfo.SnNahiyeh) {
                    selectRec = "selected";
                }
                $("#snNahiyehE").append(
                    `<option value="` +
                        element.SnMNM +
                        `" ` +
                        selectRec +
                        `>` +
                        element.NameRec +
                        `</option>`
                );
            });

            $("#snMantaghehE").empty();
            mantagheh.forEach((element, index) => {
                let selectRec = "";
                if (element.SnMNM == exactCustomerInfo.SnMantagheh) {
                    selectRec = "selected";
                }
                $("#snMantaghehE").append(
                    `<option value="` +
                        element.SnMNM +
                        `" ` +
                        selectRec +
                        `>` +
                        element.NameRec +
                        `</option>`
                );
            });
            $("#peopeladdress").val(exactCustomerInfo.peopeladdress);
            $("#password").val(exactCustomerInfo.customerPss);

            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#editNewCustomer").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });
            $("#editNewCustomer").modal("show");
        },
        error: function (data) {},
    });
});



$("#addSaleLineBtn").on("click", function () {
    $("#addSaleLineModal").modal("show");
});

$("#addSaleLineForm").on("submit", function (e) {
    $.ajax({
        method: $(this).attr("method"),
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (data) {
            $("#addSaleLineModal").modal("hide");
            $("#saleLines").empty();
            data.forEach((element, index) => {
                $("#saleLines").append(
                    `<tr onclick="setSaleLineStuff(this,` +
                        element.SaleLineSn +
                        `)"><td>` +
                        (index + 1) +
                        `</td><td>` +
                        element.LineName +
                        `</td> </tr>`
                );
            });
        },
        error: function (error) {
            alert("data server error");
        },
    });
    e.preventDefault();
});

$("#editSaleLineForm").on("submit", function (e) {
    $.ajax({
        method: $(this).attr("method"),
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (data) {
            $("#editSaleLineModal").modal("hide");
            $("#saleLines").empty();
            data.forEach((element, index) => {
                $("#saleLines").append(
                    `<tr onclick="setSaleLineStuff(this,` +
                        element.SaleLineSn +
                        `)"><td>` +
                        (index + 1) +
                        `</td><td>` +
                        element.LineName +
                        `</td> </tr>`
                );
            });
        },
        error: function (error) {
            alert("data server error");
        },
    });
    e.preventDefault();
});

$("#editSaleLineBtn").on("click", function () {
    $.ajax({
        method: "get",
        url: baseUrl + "/getSaleLine",
        data: { _token: "{{@csrf}}", saleLineSn: $("#editSaleLineBtn").val() },
        async: true,
        success: function (data) {
            $("#lineNameId").val(data[0].LineName);
            $("#SaleLineId").val(data[0].SaleLineSn);
            $("#editSaleLineModal").modal("show");
        },
        error: function (error) {},
    });
});

$("#deleteSaleLineBtn").on("click", function () {
    swal({
        title: "اخطار!",
        text: "آیا می خواهید حذف کنید؟",
        icon: "warning",
        buttons: true,
    }).then(function (willAdd) {
        if (willAdd) {
            $.ajax({
                method: "get",
                url: baseUrl + "/deleteSaleLine",
                data: {
                    _token: "{{@csrf}}",
                    saleLineSn: $("#deleteSaleLineBtn").val(),
                },
                async: true,
                success: function (data) {
                    $("#saleLines").empty();
                    data.forEach((element, index) => {
                        $("#saleLines").append(
                            `<tr onclick="setSaleLineStuff(this,` +
                                element.SaleLineSn +
                                `)"><td>` +
                                (index + 1) +
                                `</td><td>` +
                                element.LineName +
                                `</td> </tr>`
                        );
                    });
                },
                error: function (error) {},
            });
        }
    });
});
function setSaleLineStuff(element, snSaleLine) {
    $("tr").removeClass("selected");
    $(element).toggleClass("selected");
    $("#deleteSaleLineBtn").val(snSaleLine);
    $("#editSaleLineBtn").val(snSaleLine);
}

// filtering bargeri list base date
$("#bargeriFirstDate").persianDatepicker({
    cellWidth: 32,
    cellHeight: 22,
    fontSize: 14,
    formatDate: "YYYY/0M/0D",
});

$("#bargeriSecondDate").persianDatepicker({
    cellWidth: 32,
    cellHeight: 22,
    fontSize: 14,
    formatDate: "YYYY/0M/0D",
});

$("#searchBargiriSelfForm").on("submit",function(e){
    e.preventDefault()
    $.ajax({
        method: $(this).attr("method"),
        url: $(this).attr("action"),
        data: $(this).serialize(),
            success: function (msg) {
                moment.locale("en");
                $("#crmDriverBargeri").empty();
                msg.forEach((element, index) => {
                    $("#crmDriverBargeri").append(
                        `
                        <tr onclick="setBargiryStuff(this)">
                            <td>` + (index + 1) + `</td>
                            <td>` + element.Name +`</td>
                            <td class="address">` +element.peopeladdress +`</td>
                            <td><a style="color:black; font-size:12px;" href="tel:+900300400"> ` + element.PhoneStr + ` </a> </td>
                            <td style="text-align: center;"><a style="text-decoration:none;" target="_blank" href="https://maps.google.com/?q=` +
                            element.LonPers +  "," + element.LatPers + `"><i class="fas fa-map-marker-alt fa-1xl" style="color:#116bc7; "></i></a></td>
                            <td style="text-align: center; cursor:pointer;" data-toggle="modal" data-target="#factorDeatials"><i class="fa fa-eye fa-1xl"> </i> </td>
                            <td class="choice"> <input class="customerList form-check-input" name="element." type="radio" value="` +
                            element.SnBargiryBYS + "_" + element.SerialNoHDS +  "_" +  element.TotalPriceHDS + `"></td>
                        </tr>`
                    );
                });
            },
            error: function (data) {
                alert("جستجوی بارگیری مشکل دارد.");
            }
        })
    }
);
$("#addingEmtyaz").on("submit", function (e) {
    $.ajax({
        method: $(this).attr("method"),
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (data) {
            $("#creditSetting").modal("hide");
        },
        error: function (error) {
            alert("data server error");
        },
    });
    e.preventDefault();
});

$("#decreasingEmtyaz").on("submit", function (e) {
    $.ajax({
        method: $(this).attr("method"),
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (data) {
            $("#decreasingCredit").modal("hide");
        },
        error: function (error) {
            alert("data server error");
        },
    });
    e.preventDefault();
});

$("#showEmtiyazHistoryBtn").on("click", () => {
    adminId = $("#adminSn").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/showAdminEmtyazHistory",
        data: {
            _token: "{{ csrf_token() }}",
            adminID: adminId,
        },
        async: true,
        success: function (data) {
            $("#adminEmtyasHistoryBody").empty();
            data.forEach((element, index) => {
                $("#adminEmtyasHistoryBody").append(
                    `
                    <tr>
                    <td>` +  (index + 1) +  `</td>
                    <td>` +  element.name + ` ` + element.lastName + `</td>
                    <td> بازاریاب </td>
                    <td>` + element.positiveBonus + `</td>
                    <td> ` + element.negativeBonus + `</td>
                    <td>` + element.discription + `</td>
                    <td>
                    <button class="btn btn-primary btn-sm" onclick="editAdminsHistoryEmtyaz(` +
                        element.id +
                        `)"> <i class="fa fa-edit"></i>  </button>
                    </td>
                </tr>`
                );
            });

            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#adminEmtyazHistory").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });

            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#adminEmtyazHistory").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });

            $("#adminEmtyazHistory").modal("show");
        },
        error: function (error) {
            alert("data server error");
        },
    });
});

function editAdminsHistoryEmtyaz(adminEmtyasHistoryId) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getAdminHistory",
        data: {
            _token: "{{ csrf_token() }}",
            historyID: adminEmtyasHistoryId,
        },
        async: true,
        success: function (data) {
            $("#negativeEmtiyasEdit").val(data[0].negativeBonus);
            $("#historyIDEmtiyasEdit").val(data[0].id);
            $("#positiveEmtiyasEdit").val(data[0].positiveBonus);
            $("#discriptionEmtiyasEdit").val(data[0].discription);
            $("#editingEmtyaz").modal("show");
        },
        error: function () {
            alert("data server error editAdminHistory");
        },
    });
}

$("#editingEmtyazForm").on("submit", function (e) {
    $.ajax({
        method: $(this).attr("method"),
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (data) {
            $("#editingEmtyaz").modal("hide");
            $("#adminEmtyasHistoryBody").empty();
            data.forEach((element, index) => {
                $("#adminEmtyasHistoryBody").append(
                    `
                <tr>
                <td>` + (index + 1) +  `</td>
                <td>` + element.name +  ` ` + element.lastName +
                        `</td>
                <td> بازاریاب </td>
                <td>` +
                        element.positiveBonus +
                        `</td>
                <td> ` +
                        element.negativeBonus +
                        `</td>
                <td>` +
                        element.discription +
                        `</td>
                <td>
                <button class="btn btn-primary btn-sm" onclick="editAdminsHistoryEmtyaz(` +
                        element.id +
                        `)"> <i class="fa fa-edit"></i>  </button>
                </td>
            </tr>`
                );
            });
            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#adminEmtyazHistory").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });

            $("#adminEmtyazHistory").modal("show");
        },
        error: function () {
            alert("data server error");
        },
    });
    e.preventDefault();
});

function showThisDayMyCustomer(thisDayDate, iteration) {
    var date = moment();

    var currentDate = date.format("YYYY-MM-DD");
    $.ajax({
        method: "get",
        url: baseUrl + "/getThisDayMyCustomer",
        data: {
            _token: "{{ csrf_token() }}",
            thisDayDate: thisDayDate,
        },
        async: true,
        success: function (data) {
            let isDisable = "disabled";
            $("#flush-collapse" + iteration).empty();
            data.forEach((element, index) => {
                if (element.addedDate === currentDate) {
                    isDisable = "";
                } else {
                    isDisable = "disabled";
                }

                if (index == 10) {
                    $("#flush-collapse" + iteration).append(
                        `<div class="bazaryabButton">
								<button class="btn btn-sm btn-primary bazarYabaction" id="loadMore"> بیشتر ...</button>
                            </div> `
                    );
                }
                if (index >= 10) {
                    $("#flush-collapse" + iteration).append(
                        ` <div class="accordion-body showLater" style="display:none;">
                             <div class="row bazarYabcard ">
                                 <div class="bazarYabGrid">
                                    <div class="bazaryabButton"> <button class="btn btn-sm btn-info enableBtn bazarYabaction" type="button" onclick="openDashboard(` +
                            element.PSN +
                            `)"> داشبورد <i class="fal fa-dashboard"></i></button> </div>
                                    <div class="bazaryabButton"> <button class="btn btn-sm btn-warning bazarYabaction" ` +
                            isDisable +
                            ` onclick="openEditCustomerModalForm(` +
                            element.PSN +
                            `)"> ویرایش <i class="fa fa-edit"></i> </button> </div>
                                    <div class="bazaryabButton"> <button class="btn btn-sm btn-primary bazarYabaction" > ` +
                            element.Name +
                            `</button></div>
                                    <div class="bazaryabButton"> <button class="btn btn-sm btn-primary bazarYabaction" syle="text-decoration:none; color:black;"> <a href="tel:09030276259"> ` +
                            element.PhoneStr.split("-")[0] +
                            ` </a> </button></div>
                                    <div class="bazaryabButton"> <button class="btn btn-sm btn-primary bazarYabaction"> تاریخ ثبت  ` +
                            moment(element.TimeStamp, "YYYY/M/D HH:mm:ss")
                                .locale("fa")
                                .format("YYYY/M/D") +
                            `</button></div>
                                    <div class="bazaryabButton"> <button class="btn btn-sm btn-primary bazarYabaction" onclick="openAddCommentModal(` +
                            element.PSN +
                            `)"> کامنت </button></div>       
                            </div>
                                <div class="row">
                                    <div class="col-12 col-md-12 col-sm-12">
                                        <button class="btn btn-sm btn-primary me-4">` +
                            element.peopeladdress +
                            `</button>
                                    </div>
                                </div>
                        </div>
                    </div> `
                    );
                } else {
                    $("#flush-collapse" + iteration).append(
                        `
                        <div class="accordion-body">
                            <div class="row bazarYabcard">
                                <div class="bazarYabGrid">
                                    <div class="bazaryabButton"> <button class="btn btn-sm btn-info enableBtn bazarYabaction" type="button" onclick="openDashboard(` +
                            element.PSN +
                            `)"> داشبورد <i class="fal fa-dashboard"></i></button> </div>
                                        <div class="bazaryabButton"> <button class="btn btn-sm btn-warning bazarYabaction" ` +
                            isDisable +
                            ` onclick="openEditCustomerModalForm(` +
                            element.PSN +
                            `)"> ویرایش <i class="fa fa-edit"></i> </button> </div>
                                        <div class="bazaryabButton"> <button class="btn btn-sm btn-primary bazarYabaction" > ` +
                            element.Name +
                            `</button></div>
                                        <div class="bazaryabButton"> <button class="btn btn-sm btn-primary bazarYabaction" syle="text-decoration:none; color:black;"> <a href="tel:09030276259"> ` +
                            element.PhoneStr.split("-")[0] +
                            ` </a> </button></div>
                                        <div class="bazaryabButton"> <button class="btn btn-sm btn-primary bazarYabaction"> تاریخ ثبت  ` +
                            moment(element.TimeStamp, "YYYY/M/D HH:mm:ss")
                                .locale("fa")
                                .format("YYYY/M/D") +
                            `</button></div>
                                        <div class="bazaryabButton"> <button class="btn btn-sm btn-primary bazarYabaction" onclick="openAddCommentModal(` +
                            element.PSN +
                            `)"> کامنت </button></div>       
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-12 col-sm-12">
                                        <button class="btn btn-sm btn-primary me-4">` +
                            element.peopeladdress +
                            `</button>
                                    </div>
                                </div>
                            </div>
                        </div>`
                    );
                }
            });
        },
        error: function () {
            alert("show thisDay method has error");
        },
    });
}

$("#activeOrInActive").on("change", () => {
    $.ajax({
        method: "get",
        url: baseUrl + "/getActiveInactiveCustomers",
        data: {
            _token: "{{ csrf_token() }}",
            activeState: $("#activeOrInActive").val(),
            SnMantagheh: $("#searchMantagheh").val(),
        },
        async: true,
        success: function (arrayed_result) {
            $("#allCustomer").empty();
            arrayed_result.forEach((element, index) => {
                $("#allCustomer").append(
                    `
            <tr onclick="checkCheckBox(this,event)">
                <td style="">` +
                        (index + 1) +
                        `</td>
                <td style="">` +
                        element.PCode +
                        `</td>
                <td>` +
                        element.Name +
                        `</td>
                <td style="">
                <input class="form-check-input" name="customerIDs[]" type="checkbox" value="` +
                        element.PSN +
                        `" id="customerId">
                </td>
            </tr>
        `
                );
            });
        },
        error: function () {
            alert("data server error editAdminHistory");
        },
    });
});

function setGeneralTargetStuff(element, userType) {
    let selectedElement = $(element).find("input:radio").prop("checked", true);

    if (userType == 1) {
        $("#generalTargetBtn1").val($(selectedElement).val());
        $("#generalTargetBtn1").prop("disabled", false);
        $("#generalTargetBtn2").prop("disabled", true);
        $("#generalTargetBtn3").prop("disabled", true);
    }
    if (userType == 2) {
        $("#generalTargetBtn2").val($(selectedElement).val());
        $("#generalTargetBtn2").prop("disabled", false);
        $("#generalTargetBtn1").prop("disabled", true);
        $("#generalTargetBtn3").prop("disabled", true);
    }
    if (userType == 3) {
        $("#generalTargetBtn3").val($(selectedElement).val());
        $("#generalTargetBtn3").prop("disabled", false);
        $("#generalTargetBtn2").prop("disabled", true);
        $("#generalTargetBtn1").prop("disabled", true);
    }
    if (userType == 4) {
        $("#generalTargetBtn4").val($(selectedElement).val());
        $("#generalTargetBtn4").prop("disabled", false);
        $("#generalTargetBtn3").prop("disabled", true);
        $("#generalTargetBtn2").prop("disabled", true);
        $("#generalTargetBtn1").prop("disabled", true);
    }
}

function editGeneralBase(element) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getGeneralBase",
        data: {
            _token: "{{csrf_token()}}",
            baseSn: $(element).val().split("_"),
        },
        async: true,
        success: function (arrayed_result) {
            $("#baseGName").val(arrayed_result[0].baseName);
            $("#firstGTarget").val(arrayed_result[0].firstTarget);
            $("#firstGTargetBonus").val(arrayed_result[0].firstTargetBonus);
            $("#secondGTarget").val(arrayed_result[0].secondTarget);
            $("#secondGTargetBonus").val(arrayed_result[0].secondTargetBonus);
            $("#thirdGTarget").val(arrayed_result[0].thirdTarget);
            $("#thirdGTargetBonus").val(arrayed_result[0].thirdTargetBonus);
            $("#baseId").val(arrayed_result[0].SnBase);
            $("#userTypeID").val(arrayed_result[0].userType);
            $("#editingGeneralTargetModal").modal("show");
        },
        eeror: function (error) {
            alert("data server error editGeralBases");
        },
    });
}

$("#editGTarget").on("submit", function (e) {
    e.preventDefault();
    $.ajax({
        method: $(this).attr("method"),
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (data) {
            if (data[0].userType == 1) {
                $("#gtargetList1").empty();
                data.forEach((element, index) => {
                    $("#gtargetList1").append(
                        `
                <tr class="targetTableTr" onclick="setGeneralTargetStuff(this,` +
                            data.userType +
                            `)">
                    <td>` +
                            (index + 1) +
                            `</td>
                    <td>` +
                            element.baseName +
                            `</td>
                    <td>` +
                            parseInt(element.firstTarget).toLocaleString(
                                "en-us"
                            ) +
                            `</td>
                    <td>` +
                            parseInt(element.firstTargetBonus).toLocaleString(
                                "en-us"
                            ) +
                            `</td>
                    <td>` +
                            parseInt(element.secondTarget).toLocaleString(
                                "en-us"
                            ) +
                            `</td>
                    <td>` +
                            parseInt(element.secondTargetBonus).toLocaleString(
                                "en-us"
                            ) +
                            `</td>
                    <td>` +
                            parseInt(element.thirdTarget).toLocaleString(
                                "en-us"
                            ) +
                            `</td>
                    <td>` +
                            parseInt(element.thirdTargetBonus).toLocaleString(
                                "en-us"
                            ) +
                            `</td>
                    <td><input class="form-check-input" name="targetId" type="radio" value="` +
                            element.SnBase +
                            `_` +
                            element.userType +
                            `"></td>
                </tr>`
                    );
                });
            }
            if (data[0].userType == 3) {
                $("#gtargetList3").empty();
                data.forEach((element, index) => {
                    $("#gtargetList3").append(
                        `
                                <tr class="targetTableTr" onclick="setGeneralTargetStuff(this,` +
                            data.userType +
                            `)">
                                    <td>` +
                            (index + 1) +
                            `</td>
                                    <td>` +
                            element.baseName +
                            `</td>
                                    <td>` +
                            parseInt(element.firstTarget).toLocaleString(
                                "en-us"
                            ) +
                            `</td>
                                    <td>` +
                            parseInt(element.firstTargetBonus).toLocaleString(
                                "en-us"
                            ) +
                            `</td>
                                    <td>` +
                            parseInt(element.secondTarget).toLocaleString(
                                "en-us"
                            ) +
                            `</td>
                                    <td>` +
                            parseInt(element.secondTargetBonus).toLocaleString(
                                "en-us"
                            ) +
                            `</td>
                                    <td>` +
                            parseInt(element.thirdTarget).toLocaleString(
                                "en-us"
                            ) +
                            `</td>
                                    <td>` +
                            parseInt(element.thirdTargetBonus).toLocaleString(
                                "en-us"
                            ) +
                            `</td>
                                    <td> <input class="form-check-input" name="targetId" type="radio" value="` +
                            element.SnBase +
                            `_` +
                            element.userType +
                            `"></td>
                                </tr>`
                    );
                });
            }

            if (data[0].userType == 4) {
                $("#gtargetList4").empty();
                data.forEach((element, index) => {
                    $("#gtargetList4").append(
                        `
            <tr class="targetTableTr" onclick="setGeneralTargetStuff(this,` +
                            data.userType +
                            `)">
            <td>` +
                            (index + 1) +
                            `</td>
            <td>` +
                            element.baseName +
                            `</td>
            <td>` +
                            parseInt(element.firstTarget).toLocaleString(
                                "en-us"
                            ) +
                            `</td>
            <td>` +
                            parseInt(element.firstTargetBonus).toLocaleString(
                                "en-us"
                            ) +
                            `</td>
            <td>` +
                            parseInt(element.secondTarget).toLocaleString(
                                "en-us"
                            ) +
                            `</td>
            <td>` +
                            parseInt(element.secondTargetBonus).toLocaleString(
                                "en-us"
                            ) +
                            `</td>
            <td>` +
                            parseInt(element.thirdTarget).toLocaleString(
                                "en-us"
                            ) +
                            `</td>
            <td>` +
                            parseInt(element.thirdTargetBonus).toLocaleString(
                                "en-us"
                            ) +
                            `</td>
            <td> <input class="form-check-input" name="targetId" type="radio" value="` +
                            element.SnBase +
                            `_` +
                            element.userType +
                            `"></td>
            </tr>`
                    );
                });
            }

            if (data[0].userType == 2) {
                $("#gtargetList2").empty();
                data.forEach((element, index) => {
                    $("#gtargetList2").append(
                        `
                    <tr class="targetTableTr" onclick="setGeneralTargetStuff(this,` +
                            data.userType +
                            `)">
                        <td>` +
                            (index + 1) +
                            `</td>
                        <td>` +
                            element.baseName +
                            `</td>
                        <td>` +
                            parseInt(element.firstTarget).toLocaleString(
                                "en-us"
                            ) +
                            `</td>
                        <td>` +
                            parseInt(element.firstTargetBonus).toLocaleString(
                                "en-us"
                            ) +
                            `</td>
                        <td>` +
                            parseInt(element.secondTarget).toLocaleString(
                                "en-us"
                            ) +
                            `</td>
                        <td>` +
                            parseInt(element.secondTargetBonus).toLocaleString(
                                "en-us"
                            ) +
                            `</td>
                        <td>` +
                            parseInt(element.thirdTarget).toLocaleString(
                                "en-us"
                            ) +
                            `</td>
                        <td>` +
                            parseInt(element.thirdTargetBonus).toLocaleString(
                                "en-us"
                            ) +
                            `</td>
                        <td> <input class="form-check-input" name="targetId" type="radio" value="` +
                            element.SnBase +
                            `_` +
                            element.userType +
                            `"></td>
                    </tr>`
                    );
                });
            }
        },
        error: function (error) {
            alert("data server error geditGrarget");
        },
    });
});

//نمایش مشتریان جدید برای ادمین
function showThisDayCustomerForAdmin(thisDayDate, iteration) {
    var date = moment();

    var currentDate = date.format("YYYY-M-0D");

    $.ajax({
        method: "get",
        url: baseUrl + "/getThisDayCustomerForAdmin",
        data: {
            _token: "{{ csrf_token() }}",
            thisDayDate: thisDayDate,
        },
        async: true,
        success: function (data) {
            let isDisable = "disabled";
            $("#flush-collapse" + iteration).empty();
            data.forEach((element, index) => {
                if (element.addedDate === currentDate) {
                    isDisable = "";
                } else {
                    isDisable = "disabled";
                }

                if (index == 10) {
                    $("#flush-collapse" + iteration).append(
                        `<div class="bazaryabButton">
								<button class="btn btn-sm btn-primary bazarYabaction" id="loadMore"> بیشتر ...</button>
                            </div> `
                    );
                }
                if (index >= 10) {
                    $("#flush-collapse" + iteration).append(
                        ` <div class="accordion-body showLater" style="display:none;">
                             <div class="row bazarYabcard ">
                                 <div class="bazarYabGrid">
                                    <div class="bazaryabButton"> <button class="btn btn-sm btn-info enableBtn bazarYabaction" type="button" onclick="openDashboard(` +
                            element.PSN +
                            `)"> داشبورد <i class="fal fa-dashboard"></i></button> </div>
                                    <div class="bazaryabButton"> <button class="btn btn-sm btn-warning bazarYabaction" ` +
                            isDisable +
                            ` onclick="openEditCustomerModalForm(` +
                            element.PSN +
                            `)"> ویرایش <i class="fa fa-edit"></i> </button> </div>
                                    <div class="bazaryabButton"> <button class="btn btn-sm btn-primary bazarYabaction" > ` +
                            element.Name +
                            `</button></div>
                                    <div class="bazaryabButton"> <button class="btn btn-sm btn-primary bazarYabaction" syle="text-decoration:none; color:black;"> <a href="tel:09030276259"> ` +
                            element.PhoneStr.split("-")[0] +
                            ` </a> </button></div>
                                    <div class="bazaryabButton"> <button class="btn btn-sm btn-primary bazarYabaction"> تاریخ ثبت  ` +
                            moment(element.TimeStamp, "YYYY/M/D HH:mm:ss")
                                .locale("fa")
                                .format("YYYY/M/D") +
                            `</button></div>
                                    <div class="bazaryabButton"> <button class="btn btn-sm btn-primary bazarYabaction" onclick="openAddCommentModal(` +
                            element.PSN +
                            `)"> کامنت </button></div>       
                            </div>
                                <div class="row">
                                    <div class="col-12 col-md-12 col-sm-12">
                                        <button class="btn btn-sm btn-primary me-4">` +
                            element.peopeladdress +
                            `</button>
                                    </div>
                                </div>
                        </div>
                    </div> `
                    );
                } else {
                    $("#flush-collapse" + iteration).append(
                        `
                        <div class="accordion-body">
                            <div class="row bazarYabcard">
                                <div class="bazarYabGrid">
                                    <div class="bazaryabButton"> <button class="btn btn-sm btn-info enableBtn bazarYabaction" type="button" onclick="openDashboard(` +
                            element.PSN +
                            `)"> داشبورد <i class="fal fa-dashboard"></i></button> </div>
                                        <div class="bazaryabButton"> <button class="btn btn-sm btn-warning bazarYabaction" ` +
                            isDisable +
                            ` onclick="openEditCustomerModalForm(` +
                            element.PSN +
                            `)"> ویرایش <i class="fa fa-edit"></i> </button> </div>
                                        <div class="bazaryabButton"> <button class="btn btn-sm btn-primary bazarYabaction" > ` +
                            element.Name +
                            `</button></div>
                                        <div class="bazaryabButton"> <button class="btn btn-sm btn-primary bazarYabaction" syle="text-decoration:none; color:black;"> <a href="tel:09030276259"> ` +
                            element.PhoneStr.split("-")[0] +
                            ` </a> </button></div>
                                        <div class="bazaryabButton"> <button class="btn btn-sm btn-primary bazarYabaction"> تاریخ ثبت  ` +
                            moment(element.TimeStamp, "YYYY/M/D HH:mm:ss")
                                .locale("fa")
                                .format("YYYY/M/D") +
                            `</button></div>
                                        <div class="bazaryabButton"> <button class="btn btn-sm btn-primary bazarYabaction" onclick="openAddCommentModal(` +
                            element.PSN +
                            `)"> کامنت </button></div>       
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-12 col-sm-12">
                                        <button class="btn btn-sm btn-primary me-4">` +
                            element.peopeladdress +
                            `</button>
                                    </div>
                                </div>
                            </div>
                        </div>`
                    );
                }
            });
        },
    });
}
//تنظیمات امتیازات

$("#firstGTarget").on("keyup", () => {
    if (!$("#firstGTarget").val()) {
        $("#firstGTarget").val(0);
    }

    $("#firstGTarget").val(
        parseInt($("#firstGTarget").val().replace(/\,/g, "")).toLocaleString(
            "en-US"
        )
    );
});
$("#secondGTarget").on("keyup", () => {
    if (!$("#secondGTarget").val()) {
        $("#secondGTarget").val(0);
    }

    $("#secondGTarget").val(
        parseInt($("#secondGTarget").val().replace(/\,/g, "")).toLocaleString(
            "en-US"
        )
    );
});
$("#thirdGTarget").on("keyup", () => {
    if (!$("#thirdTarget").val()) {
        $("#thirdTarget").val(0);
    }

    $("#thirdGTarget").val(
        parseInt($("#thirdGTarget").val().replace(/\,/g, "")).toLocaleString(
            "en-US"
        )
    );
});

$("#firstGTargetBonus").on("keyup", () => {
    if (!$("#firstGTargetBonus").val()) {
        $("#firstGTargetBonus").val(0);
    }

    $("#firstGTargetBonus").val(
        parseInt(
            $("#firstGTargetBonus").val().replace(/\,/g, "")
        ).toLocaleString("en-US")
    );
});

$("#secondGTargetBonus").on("keyup", () => {
    if (!$("#secondGTargetBonus").val()) {
        $("#secondGTargetBonus").val(0);
    }

    $("#secondGTargetBonus").val(
        parseInt(
            $("#secondGTargetBonus").val().replace(/\,/g, "")
        ).toLocaleString("en-US")
    );
});

$("#thirdGTargetBonus").on("keyup", () => {
    if (!$("#thirdGTargetBonus").val()) {
        $("#thirdGTargetBonus").val(0);
    }

    $("#thirdGTargetBonus").val(
        parseInt(
            $("#thirdGTargetBonus").val().replace(/\,/g, "")
        ).toLocaleString("en-US")
    );
});

$("#generallimitAmount").on("keyup", () => {
    if (!$("#generallimitAmount").val()) {
        $("#generallimitAmount").val(0);
    }

    $("#generallimitAmount").val(
        parseInt(
            $("#generallimitAmount").val().replace(/\,/g, "")
        ).toLocaleString("en-US")
    );
});

$("#generalBonus").on("keyup", () => {
    if (!$("#generalBonus").val()) {
        $("#generalBonus").val(0);
    }

    $("#generalBonus").val(
        parseInt($("#generalBonus").val().replace(/\,/g, "")).toLocaleString(
            "en-US"
        )
    );
});

function setGeneralBonusStuff(element, userType) {
    let input = $(element).find("input:radio").prop("checked", true);
    $("#generalBonusBtn" + userType).val(input.val());
    $("#generalBonusBtn" + userType).prop("disabled", false);
}

function openGeneralSettingModal(element) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getGeneralBonus",
        data: {
            _token: "{{ csrf_token() }}",
            generalBonusID: $(element).val(),
        },
        async: true,
        success: function (data) {
            $("#generalBaseName").val(data.BaseName);
            $("#generalBaseId").val(data.id);
            $("#generallimitAmount").val(data.limitAmount);
            $("#generalBonus").val(data.Bonus);
            $("#generalUserType").val(data.userType);

            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#editGeneralBonusModal").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });

            $("#editGeneralBonusModal").modal("show");
        },
    });
}

$("#editGeneralBonusForm").on("submit", function (e) {
    $.ajax({
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (data) {
            console.log(data);
            if (data[0].userType == 3) {
                $("#generalBonusList3").empty();
                data.forEach((element, index) => {
                    $("#generalBonusList3").append(
                        `
                        <tr onclick="setGeneralBonusStuff(this,` +
                            element.userType +
                            `">
                        <td  style="width:100px;">` +
                            (index + 1) +
                            `</td>
                        <td>` +
                            element.BaseName +
                            `</td>
                        <td>` +
                            element.Bonus +
                            `</td>
                        <td>` +
                            parseInt(element.limitAmount).toLocaleString(
                                "en-US"
                            ) +
                            `</td>
                        <td> <input class="form-check-input" name="generalBonusId" type="radio" value="` +
                            element.id +
                            `"></td>
                        </tr>`
                    );
                });
            }

            if (data[0].userType == 1) {
                $("#generalBonusList1").empty();
                data.forEach((element, index) => {
                    $("#generalBonusList1").append(
                        `
                        <tr onclick="setGeneralBonusStuff(this,` +
                            element.userType +
                            `">
                        <td  style="width:100px;">` +
                            (index + 1) +
                            `</td>
                        <td>` +
                            element.BaseName +
                            `</td>
                        <td>` +
                            element.Bonus +
                            `</td>
                        <td>` +
                            parseInt(element.limitAmount).toLocaleString(
                                "en-US"
                            ) +
                            `</td>
                        <td> <input class="form-check-input" name="generalBonusId" type="radio" value="` +
                            element.id +
                            `"></td>
                        </tr>`
                    );
                });
            }
            if (data[0].userType == 2) {
                $("#generalBonusList2").empty();
                data.forEach((element, index) => {
                    $("#generalBonusList2").append(
                        `
                        <tr onclick="setGeneralBonusStuff(this,` +
                            element.userType +
                            `">
                        <td  style="width:100px;">` +
                            (index + 1) +
                            `</td>
                        <td>` +
                            element.BaseName +
                            `</td>
                        <td>` +
                            element.Bonus +
                            `</td>
                        <td>` +
                            parseInt(element.limitAmount).toLocaleString(
                                "en-US"
                            ) +
                            `</td>
                        <td> <input class="form-check-input" name="generalBonusId" type="radio" value="` +
                            element.id +
                            `"></td>
                        </tr>`
                    );
                });
            }
            if (data[0].userType == 4) {
                $("#generalBonusList4").empty();
                data.forEach((element, index) => {
                    $("#generalBonusList4").append(
                        `
                        <tr onclick="setGeneralBonusStuff(this,` +
                            element.userType +
                            `)">
                        <td  style="width:100px;">` +
                            (index + 1) +
                            `</td>
                        <td>` +
                            element.BaseName +
                            `</td>
                        <td>` +
                            element.Bonus +
                            `</td>
                        <td>` +
                            parseInt(element.limitAmount).toLocaleString(
                                "en-US"
                            ) +
                            `</td>
                        <td> <input class="form-check-input" name="generalBonusId" type="radio" value="` +
                            element.id +
                            `"></td>
                        </tr>`
                    );
                });
            }
        },
    });
    e.preventDefault();
});

function getTodayBuyAghlamPoshtiban(adminID, lastDate, limitAmount) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getTodayBuyAghlamPoshtiban",
        data: {
            _token: "{{ csrf_token() }}",
            adminID: adminID,
        },
        async: true,
        success: function (arrayed_result) {
            $("#today_aghlam_list").empty();
            arrayed_result.forEach((element, index) => {
                $("#today_aghlam_list").append(
                    `
                        <div class="row mb-2"> 
                            <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button"><a href="#">  ` +
                        (index + 1) +
                        `</a></button> </div>
                            <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button"> <a href="">` +
                        element.GoodName +
                        `</a> </button> </div>
                            
                            <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button" onclick="openKalaDashboard(` +
                        element.GoodSn +
                        `)"><a href="#">  داشبورد خرید</a> </button> </div>
                        </div>
                    `
                );
            });
        },
    });
}

function getTodayBuyAghlamDriver(adminId, emptyDate, limitAmount) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getDriverTodayAghlam",
        data: {
            _token: "{{@csrf}}",
            driverId: adminId,
            emptyDate: "" + emptyDate + "",
        },
        async: true,
        success: function (arrayed_result) {
            $("#today_aghlam_list").empty();
            arrayed_result.forEach((element, index) => {
                $("#today_aghlam_list").append(
                    `
            <div class="row mb-2"> 
            <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button"><a href="#">  ` +
                        (index + 1) +
                        `</a></button> </div>
                <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button"> <a href="">` +
                        element.GoodName +
                        `</a> </button> </div>
                
                <div class="col-4 col-sm-4">  <button class="btn btn-info btn-sm nasb-button" onclick="openKalaDashboard(` +
                        element.GoodSn +
                        `)"><a href="#">  داشبورد خرید</a> </button> </div>
            </div>
            `
                );
            });
        },
        error: function (error) {
            alert("error in getting data");
        },
    });
}

function getAllBuyAghlamDriver(adminId, emptyDate, limitAmount) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getDriverAllAghlam",
        data: {
            _token: "{{@csrf}}",
            driverId: adminId,
            emptyDate: "" + emptyDate + "",
        },
        async: true,
        success: function (arrayed_result) {
            console.log(arrayed_result);
            $("#all_aghlam_list").empty();
            arrayed_result.forEach((element, index) => {
                $("#all_aghlam_list").append(
                    `
                <div class="row mb-2"> 
                    <div class="col-4 col-sm-4 px-1">  <button class="btn btn-info btn-sm nasb-button"><a href="#"> ` +
                        (index + 1) +
                        `</a></button> </div>
                    <div class="col-4 col-sm-4 px-1">  <button class="btn btn-info btn-sm nasb-button"> <a href="">` +
                        element.GoodName +
                        `</a> </button> </div>
                    <div class="col-4 col-sm-4 px-1">  <button class="btn btn-info btn-sm nasb-button" onclick="openKalaDashboard(` +
                        element.GoodSn +
                        `)"><a href="#">  داشبورد خرید</a> </button> </div>
                </div>
                `
                );
            });
        },
        error: function (error) {
            alert("error in getting data");
        },
    });
}

function getAllFactorDriver(adminId, emptyDate, limitAmount) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getAllFactorDriver",
        data: {
            _token: "{{@csrf}}",
            driverId: adminId,
            emptyDate: "" + emptyDate + "",
        },
        async: true,
        success: function (arrayed_result) {
            console.log(arrayed_result);
            $("#all_factor_list").empty();
            arrayed_result.forEach((element, index) => {
                $("#all_factor_list").append(
                    `
                <div class="row mb-2"> 
                    <div class="col-4 col-sm-4 px-1">  <button class="btn btn-info btn-sm nasb-button"><a href="#"> ` +
                        (index + 1) +
                        `</a></button> </div>
                    <div class="col-4 col-sm-4 px-1">  <button class="btn btn-info btn-sm nasb-button"> <a href="">` +
                        element.Name +
                        `</a> </button> </div>
                    <div class="col-4 col-sm-4 px-1">  <button class="btn btn-info btn-sm nasb-button"> <a href="">` +
                        element.FactDate +
                        `</a> </button> </div>
                    <div class="col-4 col-sm-4 px-1">  <button class="btn btn-info btn-sm nasb-button" onclick="openKalaDashboard(` +
                        element.SnGood +
                        `)"><a href="#">  داشبورد کالا</a> </button> </div>
                </div>
                `
                );
            });
        },
        error: function (error) {
            alert("error in getting data");
        },
    });
}

function getTodayDriverFactors(adminId, emptyDate, limitAmount) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getTodayDriverFactors",
        data: {
            _token: "{{@csrf}}",
            driverId: adminId,
            emptyDate: "" + emptyDate + "",
        },
        async: true,
        success: function (arrayed_result) {
            console.log(arrayed_result);
            $("#today_factor_list").empty();
            arrayed_result.forEach((element, index) => {
                $("#today_factor_list").append(
                    `
                <div class="row mb-2"> 
                    <div class="col-4 col-sm-4 px-1">  <button class="btn btn-info btn-sm nasb-button"><a href="#"> ` +
                        (index + 1) +
                        `</a></button> </div>
                    <div class="col-4 col-sm-4 px-1">  <button class="btn btn-info btn-sm nasb-button"> <a href="">` +
                        element.Name +
                        `</a> </button> </div>
                    <div class="col-4 col-sm-4 px-1">  <button class="btn btn-info btn-sm nasb-button"> <a href="">` +
                        element.FactDate +
                        `</a> </button> </div>
                    <div class="col-4 col-sm-4 px-1">  <button class="btn btn-info btn-sm nasb-button" onclick="openKalaDashboard(` +
                        element.SnGood +
                        `)"><a href="#">  داشبورد کالا</a> </button> </div>
                </div>
                `
                );
            });
        },
        error: function (error) {
            alert("error in getting data");
        },
    });
}

$("#driverServicesBtn").on("click", () => {
    $.ajax({
        method: "get",
        url: baseUrl + "/getInfoForDriverService",
        data: { _token: "{{@csrf}}" },
        async: true,
        success: function (data) {
            $("#selectDriver").empty();
            data.forEach((element, index) => {
                $("#selectDriver").append(
                    `
                    <option value="` +
                        element.driverId +
                        `">` +
                        element.name +
                        ` ` +
                        element.lastName +
                        `</option>`
                );
            });
        },
        error: function (error) {
            alert("error in getting data.");
        },
    });

    if (!$(".modal.in").length) {
        $(".modal-dialog").css({
            top: 0,
            left: 0,
        });
    }
    $("#driverServicesModal").modal({
        backdrop: false,
        show: true,
    });

    $(".modal-dialog").draggable({
        handle: ".modal-header",
    });

    $("#driverServicesModal").modal("show");
});

$("#addService").on("submit", function (e) {
    $.ajax({
        method: $(this).attr("method"),
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (data) {
            $("#driverServiceBodyList").empty();
            data.forEach((element, index) => {
                let serviceType = "";
                if (element.serviceType == 1) {
                    serviceType = "دور";
                }
                if (element.serviceType == 2) {
                    serviceType = "متوسط";
                }
                if (element.serviceType == 3) {
                    serviceType = "نزدیک";
                }
                $("#driverServiceBodyList").append(
                    `
                        <tr onclick="setDriverServiceStuff(this,` +
                        element.ServiceSn +
                        `)">
                            <th>` +
                        (index + 1) +
                        `</th>
                            <td> ` +
                        element.name +
                        ` ` +
                        element.lastName +
                        `</td>
                            <td>` +
                        serviceType +
                        `</td>
                            <td>` +
                        element.discription +
                        `</td>
                            <td>` +
                        moment(element.TimeStamp, "YYYY/M/D HH:mm:ss")
                            .locale("fa")
                            .format("YYYY/M/D HH:mm:ss") +
                        `</td>
                            <td>  <input  type="radio" name="radioBtn" value="` +
                        element.ServiceSn +
                        `"> </td>
                        </tr>`
                );
            });
            $("#driverServicesModal").modal("hide");
        },
        error: function (error) {
            console.log(error);
        },
    });

    e.preventDefault();
});

function setDriverServiceStuff(element, serviceId) {
    $("tr").removeClass("selected");
    $(element).toggleClass("selected");
    $("#serviceSn").val(serviceId);
    $("#editDriverServicesBtn").prop("disabled", false);
}

$("#editDriverServicesBtn").on("click", () => {
    $.ajax({
        method: "get",
        url: baseUrl + "/getServiceInfo",
        async: true,
        data: { _token: "{{@csrf}}", serviceId: $("#serviceSn").val() },
        success: function (data) {
            if (data[0][0].serviceType == 3) {
                $("#weakService").prop("selected", true);
            }
            if (data[0][0].serviceType == 2) {
                $("#mediumService").prop("selected", true);
            }
            if (data[0][0].serviceType == 1) {
                $("#strongService").prop("selected", true);
            }
            $("#editDiscription").val(data[0][0].discription);

            $("#editDriverSn").empty();
            data[1].forEach((element) => {
                if (data[0][0].adminId == element.driverId) {
                    $("#editDriverSn").append(
                        `<option selected value="` +
                            element.driverId +
                            `">` +
                            element.name +
                            ` ` +
                            element.lastName +
                            `</option>`
                    );
                } else {
                    $("#editDriverSn").append(
                        `<option value="` +
                            element.driverId +
                            `">` +
                            element.name +
                            ` ` +
                            element.lastName +
                            `</option>`
                    );
                }
            });

            if (!$(".modal.in").length) {
                $(".modal-dialog").css({
                    top: 0,
                    left: 0,
                });
            }
            $("#editDriverServicModal").modal({
                backdrop: false,
                show: true,
            });

            $(".modal-dialog").draggable({
                handle: ".modal-header",
            });

            $("#editDriverServicModal").modal("show");
        },
        error: function (error) {
            alert("bad");
        },
    });
});

$("#editServiceForm").on("submit", function (e) {
    $.ajax({
        method: $(this).attr("method"),
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (data) {
            $("#driverServiceBodyList").empty();
            data.forEach((element, index) => {
                let serviceType = "";
                if (element.serviceType == 1) {
                    serviceType = "دور";
                }
                if (element.serviceType == 2) {
                    serviceType = "متوسط";
                }
                if (element.serviceType == 3) {
                    serviceType = "نزدیک";
                }
                $("#driverServiceBodyList").append(
                    `
                        <tr onclick="setDriverServiceStuff(this,` +
                        element.ServiceSn +
                        `)">
                            <th>` +
                        (index + 1) +
                        `</th>
                            <td> ` +
                        element.name +
                        ` ` +
                        element.lastName +
                        `</td>
                            <td>` +
                        serviceType +
                        `</td>
                            <td>` +
                        element.discription +
                        `</td>
                            <td>` +
                        moment(element.TimeStamp, "YYYY/M/D HH:mm:ss")
                            .locale("fa")
                            .format("YYYY/M/D HH:mm:ss") +
                        `</td>
                            <td>  <input  type="radio" name="radioBtn" value="` +
                        element.ServiceSn +
                        `"> </td>
                        </tr>`
                );
            });
            $("#editDriverServicModal").modal("hide");
        },
        error: function (error) {
            alert("error getting data");
        },
    });
    e.preventDefault();
});

$("#getServiceSearchForm").on("submit", function (e) {
    e.preventDefault();
    $.ajax({
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (data) {
            $("#driverServiceBodyList").empty();
            data.forEach((element, index) => {
                let serviceType = "";
                if (element.serviceType == 1) {
                    serviceType = "دور";
                }
                if (element.serviceType == 2) {
                    serviceType = "متوسط";
                }
                if (element.serviceType == 3) {
                    serviceType = "نزدیک";
                }
                $("#driverServiceBodyList").append(
                    `
                        <tr onclick="setDriverServiceStuff(this,` +
                        element.ServiceSn +
                        `)">
                            <th>` +
                        (index + 1) +
                        `</th>
                            <td> ` +
                        element.name +
                        ` ` +
                        element.lastName +
                        `</td>
                            <td>` +
                        serviceType +
                        `</td>
                            <td>` +
                        element.discription +
                        `</td>
                            <td>` +
                        element.TimeStamp +
                        `</td>
                            <td>  <input  type="radio" name="radioBtn" value="` +
                        element.ServiceSn +
                        `"> </td>
                        </tr>`
                );
            });
        },
        error: function (error) {},
    });
});

$("#orderDriverServices").on("change", function () {
    $.ajax({
        method: "get",
        url: baseUrl + "/serviceOrder",
        async: true,
        data: {
            _token: "{{@csrf}}",
            selectedBase: $("#orderDriverServices").val(),
        },
        success: function (data) {
            $("#driverServiceBodyList").empty();
            data.forEach((element, index) => {
                let serviceType = "";
                if (element.serviceType == 1) {
                    serviceType = "دور";
                }
                if (element.serviceType == 2) {
                    serviceType = "متوسط";
                }
                if (element.serviceType == 3) {
                    serviceType = "نزدیک";
                }
                $("#driverServiceBodyList").append(
                    `<tr onclick="setDriverServiceStuff(this,` +
                        element.ServiceSn +
                        `)">
                            <td>` +
                        (index + 1) +
                        `</td>
                            <td> ` +
                        element.name +
                        ` ` +
                        element.lastName +
                        `</td>
                            <td>` +
                        serviceType +
                        `</td>
                            <td>` +
                        element.discription +
                        `</td>
                            <td>` +
                        element.TimeStamp +
                        `</td>
                            <td>  <input  type="radio" name="radioBtn" value="` +
                        element.ServiceSn +
                        `"> </td>
                        </tr>`
                );
            });
        },
        error: function (error) {},
    });
});
function getServices(flag) {
    $.ajax({
        method: "get",
        url: baseUrl + "/getDriverServices",
        async: true,
        data: {
            _token: "{{@csrf}}",
            flag: flag,
        },
        success: function (data) {
            console.log(data);
            $("#driverServiceBodyList").empty();
            data.forEach((element, index) => {
                let serviceType = "";
                if (element.serviceType == 1) {
                    serviceType = "دور";
                }
                if (element.serviceType == 2) {
                    serviceType = "متوسط";
                }
                if (element.serviceType == 3) {
                    serviceType = "نزدیک";
                }
                $("#driverServiceBodyList").append(
                    `<tr onclick="setDriverServiceStuff(this,` +
                        element.ServiceSn +
                        `)">
                            <td>` +
                        (index + 1) +
                        `</td>
                            <td> ` +
                        element.name +
                        ` ` +
                        element.lastName +
                        `</td>
                            <td>` +
                        serviceType +
                        `</td>
                            <td>` +
                        element.discription +
                        `</td>
                            <td>` +
                        element.TimeStamp +
                        `</td>
                            <td>  <input  type="radio" name="radioBtn" value="` +
                        element.ServiceSn +
                        `"> </td>
                        </tr>`
                );
            });
        },
        error: function (error) {},
    });
}

function setUpDownHistoryStuff(element, historyID) {
    $("tr").removeClass("selected");
    $(element).toggleClass("selected");
    $.ajax({
        method: "get",
        url: baseUrl + "/getUpDownBonusInfo",
        data: {
            _token: "{{@csrf}}",
            historyID: historyID,
        },
        async: true,
        success: function (respond) {
            console.log("get up down bonus info")
        },
        error: function (error) {
            console.log("get up down bonus info")
        },
    });
}

$("#deleteCreditBtn").on("click",function(){

    swal({
        title: "اخطار!",
        text: "آیا می خواهید حذف شود؟",
        icon: "warning",
        buttons: true,
    }).then(function (willAdd) {
        if (willAdd) {
            $.ajax({method:'get',
            url:baseUrl+'/deleteUpDownBonus',
            data:{
                _token:"{{@csrf}}",
                historyId:$("#deleteCreditBtn").val()},
            async:true,
            success:function(respond){
                $("#historyListBody").empty();
                respond.forEach((element,index)=>{
                    bonus=0;
                    color="";
                    if(element.positiveBonus>0){
                        bonus=element.positiveBonus;
                    }else{
                        color="red";
                        bonus=element.negativeBonus;
                    }
                    $("#historyListBody").append(`  <tr onclick="setUpDownHistoryStuff(this,`+element.historyId+`)">
                                                        <td>`+(index+1)+`</td>
                                                        <td> `+element.TimeStamp+` </td>
                                                        <td>`+element.adminName+`</td>
                                                        <td style="color:`+color+`">`+bonus+`</td>
                                                        <td>`+element.superName+`</td>
                                                    </tr>`);
                });
            },
            error:function(error){}
            });
        }
    });
});

$("#editCreditBtn").on("click",function(){

    $.ajax({method:'get',
                url:baseUrl+'/getUpDownBonusInfo',
                data:{
                    _token:"{{@csrf}}",
                    historyID:$("#editCreditBtn").val()},
                async:true,
                success:function(respond){
                    $("#adminBonusTaker").empty();
                    respond[1].forEach((element,index)=>{
                        isSelected="";
                        if(respond[0][0].adminId==element.id){
                            isSelected="selected";
                        }
                        $("#adminBonusTaker").append(`<option `+isSelected+` value="`+element.id+`">`+element.name+` `+element.lastName+`</option>`);
                    });
                    if(respond[0][0].positiveBonus>0){
                        $("#pBonus").prop("disabled",false);
                        $("#nBonus").prop("disabled",true);
                        $("#pBonus").val(respond[0][0].positiveBonus);
                        $("#nBonus").val(0);
                        $("#commentBonus").val("");
                        $("#commentBonus").val(respond[0][0].discription);
                    }else{
                        $("#pBonus").prop("disabled",true);
                        $("#nBonus").prop("disabled",false);
                        $("#nBonus").val(respond[0][0].negativeBonus);
                        $("#pBonus").val(0);
                        $("#commentBonus").val("");
                        $("#commentBonus").val(respond[0][0].discription);
                    }
                    $("#historyId").val(respond[0][0].id);
                    $("#editingCredit").modal("show");
                },
                error:function(error){
                    alert(error);
                }
            }); 
});

$("#editEmtyaz").on("submit",function(e){
    e.preventDefault();
    $.ajax({
        url: $(this).attr("action"),
        data: $(this).serialize(),
        success: function (respond) {
            $("#historyListBody").empty();
            respond.forEach((element,index)=>{
                bonus=0;
                color="";
                if(element.positiveBonus>0){
                    bonus=element.positiveBonus;
                }else{
                    color="red";
                    bonus=element.negativeBonus;
                }
                $("#historyListBody").append(`  <tr onclick="setUpDownHistoryStuff(this,`+element.historyId+`)">
                                                    <td>`+(index+1)+`</td>
                                                    <td> `+element.TimeStamp+` </td>
                                                    <td>`+element.adminName+`</td>
                                                    <td style="color:`+color+`">`+bonus+`</td>
                                                    <td>`+element.superName+`</td>
                                                </tr>`);
            });
        }
        ,error:function(error){}
    });
});

function getUpDownHistory(flag) {
    $.ajax({
        method:'get',
        url:baseUrl+'/getUpDownBonusHistory',
        data:{_token:"{{@csrf}}",
                flag:flag},
        async:true,
        success:function(data){
            $("#historyListBody").empty();
            data.forEach((element,index)=>{
                bonus=0;
                color="";
                if(element.positiveBonus>0){
                    bonus=element.positiveBonus;
                }else{
                    color="red";
                    bonus=element.negativeBonus;
                }
                $("#historyListBody").append(`<tr onclick="setUpDownHistoryStuff(this,`+element.historyId+`)">
                <td>`+(index+1)+`</td>
                <td> `+element.TimeStamp+` </td>
                <td>`+element.adminName+`</td>
                <td style="color:`+color+`">`+bonus+`</td>
                <td>`+element.superName+`</td>
                </tr>`);
            });
        },
        error:function(error){

        }
    });
}
$("#orderBonusHistory").on("change",function(){
    $.ajax({
        method:'get',
        url:baseUrl+"/orderUpDownHistory",
        data:{_token:"{{@csrf}}",
            baseName:$("#orderBonusHistory").val()},
        async:true,
        success:function(data){
            $("#historyListBody").empty();
            data.forEach((element,index)=>{
                bonus=0;
                color="";
                if(element.positiveBonus>0){
                    bonus=element.positiveBonus;
                }else{
                    color="red";
                    bonus=element.negativeBonus;
                }
                $("#historyListBody").append(`<tr onclick="setUpDownHistoryStuff(this,`+element.historyId+`)">
                <td>`+(index+1)+`</td>
                <td> `+element.TimeStamp+` </td>
                <td>`+element.adminName+`</td>
                <td style="color:`+color+`">`+bonus+`</td>
                <td>`+element.superName+`</td>
                </tr>`);
            });
        },
        error:function(error){

        }
    })
})
$("#searchUpDownHistoryName").on("keyup",function(){
$.ajax({
    method:'get',
    url:baseUrl+'/searchUpDownBonusByName',
    data:{
        _token:"{{@csrf}}",
        searchTerm:$("#searchUpDownHistoryName").val()
    },
    async:true,
    success:function(data){
        $("#historyListBody").empty();
        data.forEach((element,index)=>{
            bonus=0;
            color="";
            if(element.positiveBonus>0){
                bonus=element.positiveBonus;
            }else{
                color="red";
                bonus=element.negativeBonus;
            }
            $("#historyListBody").append(`<tr onclick="setUpDownHistoryStuff(this,`+element.historyId+`)">
            <td>`+(index+1)+`</td>
            <td> `+element.TimeStamp+` </td>
            <td>`+element.adminName+`</td>
            <td style="color:`+color+`">`+bonus+`</td>
            <td>`+element.superName+`</td>
            </tr>`);
        });
    },
    error:function(error){

    }
    });
});

$("#getHistorySearchBtn").on("click",function(e){
    bonusType="";
    if($("#positiveBonusRadio").is(":checked")){
        bonusType="positive";
    }
    if($("#negativeBonusRadio").is(":checked")){
        bonusType="negative";
    }
    firstDate=$("#firstDateReturned").val();
    secondDate=$("#secondDateReturned").val();
    e.preventDefault();
    $.ajax({
        method:'get',
        url: baseUrl+'/getHistorySearch',
        data: {
            _token:"{{@csrf}}",
            bonusType:bonusType,
            firstDate:firstDate,
            secondDate:secondDate
        },
        success: function (data) {
            $("#historyListBody").empty();
            data.forEach((element,index)=>{
                bonus=0;
                color="";
                if(element.positiveBonus>0){
                    bonus=element.positiveBonus;
                }else{
                    color="red";
                    bonus=element.negativeBonus;
                }
                $("#historyListBody").append(`<tr onclick="setUpDownHistoryStuff(this,`+element.historyId+`)">
                <td>`+(index+1)+`</td>
                <td> `+element.TimeStamp+` </td>
                <td>`+element.adminName+`</td>
                <td style="color:`+color+`">`+bonus+`</td>
                <td>`+element.superName+`</td>
                </tr>`);
            });
        },
        error:function(error){
            console.log(error)
        }
    });
});

$("#assesToday").on("change", () => {
    if ($("#assesToday").is(":checked")) {
        $("#assesSecondDate").prop("disabled", true);
        $("#assesFirstDate").prop("disabled", true);
        $("#assesDoneT").css({ display: "none" });
        $(".donComment").css({ display: "none" });
        $("#assesNotDone").css({ display: "block" });
    } else {
        $("#assesSecondDate").prop("disabled", false);
        $("#assesFirstDate").prop("disabled", false);
    }
});

$("#assesPast").on("change", () => {
    if ($("#assesPast").is(":checked")) {
        $("#assesSecondDate").prop("disabled", false);
        $("#assesFirstDate").prop("disabled", false);
        $("#assesDoneT").css({ display: "none" });
        $(".donComment").css({ display: "none" });
        $("#assesNotDone").css({ display: "block" });
    }
});

$("#assesDone").on("change", () => {
    if ($("#assesDone").is(":checked")) {
        $("#assesNotDone").css({ display: "none" });
        $(".donComment").css({ display: "inline" });
        $("#assesDoneT").css({ display: "block" });
    }
});

$("#getAssesBtn").on("click", function () {
    let assesDay = "today";
    if ($("#assesToday").is(":checked")) {
        assesDay = "today";
    }
    if ($("#assesPast").is(":checked")) {
        assesDay = "past";
    }
    if ($("#assesDone").is(":checked")) {
        assesDay = "done";
    }

    let assescustomerName = $("#assescustomerName").val();
    let fromDate = $("#assesFirstDate").val();
    let toDate = $("#assesSecondDate").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/getAsses",
        async: true,
        data: {
            _token: "{{@csrf}}",
            dayAsses: assesDay,
            assescustomerName: assescustomerName,
            formatDate: "" + fromDate + "",
            toDate: "" + toDate + "",
        },
        success: function (response) {
            console.log(response);
            if (assesDay != "done") {
                $("#customersAssesBody").empty();
                response.forEach((element, index) => {
                    $("#customersAssesBody").append(
                        `
                <tr onclick="assesmentStuff(this)">
                    <td class="no-sort">` +
                            (index + 1) +
                            `</td>
                    <td>` +
                            element.Name +
                            `</td>
                    <td>` +
                            parseInt(element.TotalPriceHDS / 10).toLocaleString(
                                "en-us"
                            ) +
                            ` تومان</td>
                    <td>` +
                            element.FactDate +
                            `</td>
                    <td>` +
                            element.FactNo +
                            `</td>
                    <td> <input class="customerList form-check-input" name="factorId" type="radio" value="` +
                            element.PSN +
                            `_` +
                            element.SerialNoHDS +
                            `"></td>
                </tr>`
                    );
                });
            } else {
                $("#customerListBodyDone").empty();
                response.forEach((element, index) => {
                    $("#customerListBodyDone").append(
                        `
                        <tr  onclick="showDoneCommentDetail(this)">
                            <td>` +
                            (index + 1) +
                            `</td>
                            <td>` +
                            element.Name +
                            `</td>
                            <td>` +
                            element.PhoneStr +
                            `</td>
                            <td>` +
                            moment(element.TimeStamp, "YYYY/M/D HH:mm:ss")
                                .locale("fa")
                                .format("HH:mm:ss YYYY/M/D") +
                            `</td>
                            <td>` +
                            element.AdminName +
                            ` ` +
                            element.lastName +
                            `</td>
                            <td> <input class="customerList form-check-input" name="factorId" type="radio" value="` +
                            element.PSN +
                            `_` +
                            element.SerialNoHDS +
                            `"></td>
                        </tr>`
                    );
                });
            }
        },
        error: function (error) {},
    });
});

$("#settingAndTargetRadio").on("change", () => {
    $("#targetAndSettingContent").css("display", "block");
    $("#elseSettings").css("display", "none");
});
$("#elseSettingsRadio").on("change", () => {
    $("#elseSettings").css("display", "block");
    $("#targetAndSettingContent").css("display", "none");
});

$("#firstManger").on("change", () => {
    $("#relatedHeadOfficer").css("display", "flex");
});
$("#firstHeadOfficer").on("change", () => {
    $("#tableGroupList").css("display", "block");
    $(".forSecondHeadOfficer").css("display", "none");
});

$("#secondHeadOfficer").on("change", () => {
    $(".forSecondHeadOfficer").css("display", "block");
    $("#tableGroupList").css("display", "none");
});

$("#karbarnRadioBtn").on("change", () => {
    $("#karbaranActionContainer").css("display", "block");
    $("#lowlevelEmployee").css("display", "none");
});
$("#bazarYabRadioBtn").on("change", () => {
    $("#lowlevelEmployee").css("display", "block");
    $("#karbaranActionContainer").css("display", "none");
});

$("#allCustomerReportRadio").on("change", () => {
    $("#staffVisitor").css("display", "none");
    $("#loginTosystemReport").css("display", "none");
    $("#allCustomerStaff").css("display", "inline");
    $("#customerActionTable").css("display", "inline");
    $(".inActiveBtn").css("display", "none");
    $(".customerDashboarBtn ").css("display", "inline");
    $("#inActiveTools").css("display", "none");
    $("#inActiveCustomerTable").css("display", "none");
    $(".referencialTools").css("display", "none");
    $(".evcuatedCustomer").css("display", "none");
    $(".referencialReport").css("display", "none");
    $(".inactiveReport").css("display", "none");
    $("#orderAll").css("display", "inline");
    $("#orderLogins").css("display", "none");
    $("#orderNoAdmins").css("display", "none");
    $("#orderInActives").css("display", "none");
    $("#orderReturn").css("display", "none");
});

$("#customerLoginReportRadio").on("change", () => {
    $("#staffVisitor").css("display", "flex");
    $("#loginTosystemReport").css("display", "block");
    $(".loginReport").css("display", "inline");
    $("#allCustomerStaff").css("display", "none");
    $("#customerActionTable").css("display", "none");
    $(".inActiveBtn").css("display", "none");
    $(".customerDashboarBtn ").css("display", "none");
    $("#inActiveTools").css("display", "none");
    $("#inActiveCustomerTable").css("display", "none");
    $(".referencialTools").css("display", "none");
    $(".evcuatedCustomer").css("display", "none");
    $(".referencialReport").css("display", "none");
    $(".inactiveReport").css("display", "none");
    
    $("#orderAll").css("display", "none");
    $("#orderLogins").css("display", "inline");
    $("#orderNoAdmins").css("display", "none");
    $("#orderInActives").css("display", "none");
    $("#orderReturn").css("display", "none");

});

$("#customerInactiveRadio").on("change", () => {
    $("#inActiveTools").css("display", "block");
    $(".inactiveReport").css("display", "inline");
    $("#allCustomerStaff").css("display", "none");
    $(".customerDashboarBtn").css("display", "none");
    $(".inActiveBtn").css("display", "inline");
    $("#inActiveCustomerTable").css("display", "block");
    $("#customerActionTable").css("display", "none");
    $("#loginTosystemReport").css("display", "none");
    $("#staffVisitor").css("display", "none");
    $(".evcuatedCustomer").css("display", "none");
    $(".referencialTools").css("display", "none");
    $(".referencialReport").css("display", "none");
    $(".loginReport").css("display", "none");

    $("#orderAll").css("display", "none");
    $("#orderLogins").css("display", "none");
    $("#orderNoAdmins").css("display", "none");
    $("#orderInActives").css("display", "inline");
    $("#orderReturn").css("display", "none");

});
$("#evacuatedCustomerRadio").on("change", () => {
    $(".evcuatedCustomer").css("display", "inline");
    $("#allCustomerStaff").css("display", "none");
    $(".customerDashboarBtn").css("display", "none");
    $(".inActiveBtn").css("display", "none");
    $("#inActiveCustomerTable").css("display", "none");
    $("#customerActionTable").css("display", "none");
    $("#inActiveTools").css("display", "none");
    $("#loginTosystemReport").css("display", "none");
    $("#staffVisitor").css("display", "none");
    $(".referencialTools").css("display", "none");
    $(".referencialReport").css("display", "none");
    $(".loginReport").css("display", "none");
    $(".inactiveReport").css("display", "none");

    $("#orderAll").css("display", "none");
    $("#orderLogins").css("display", "none");
    $("#orderNoAdmins").css("display", "inline");
    $("#orderInActives").css("display", "none");
    $("#orderReturn").css("display", "none");
});

$("#referentialCustomerRadio").on("change", () => {
    $(".referencialTools").css("display", "inline");
    $(".referencialReport").css("display", "inline");
    $(".evcuatedCustomer").css("display", "none");
    $("#allCustomerStaff").css("display", "none");
    $(".customerDashboarBtn").css("display", "none");
    $(".inActiveBtn").css("display", "none");
    $("#inActiveCustomerTable").css("display", "none");
    $("#customerActionTable").css("display", "none");
    $("#inActiveTools").css("display", "none");
    $("#loginTosystemReport").css("display", "none");
    $(".loginReport").css("display", "none");
    $("#staffVisitor").css("display", "none");
    $(".inactiveReport").css("display", "none");

    $("#orderAll").css("display", "none");
    $("#orderLogins").css("display", "none");
    $("#orderNoAdmins").css("display", "none");
    $("#orderInActives").css("display", "none");
    $("#orderReturn").css("display", "inline");
});

$(".alarmRighRdios").on("change", () => {
    
    if($("#customerWithOutAlarm").is(":checked")){
        $("#customerWithOutAlarmBuyOrNot").css("display", "block");
        $("#unAlarmedCustomers").css("display", "block");
        $("#alarmedCustomers").css("display", "none");
        $("#alarmDates").css("display", "none");
        $("#alarmBuysDates") .css("display", "block");
        $("#alamButtonsHistoryDiv").css("display", "none");
        $("#noAlarmButtonsHistoryDiv").css("display", "block");
        $(".alarmBtn").css("display", "none");
        $("#orderUnAlarms").css("display", "block");
    }else{
        $("#customerWithOutAlarmBuyOrNot").css("display", "none");
        $("#unAlarmedCustomers").css("display", "none"); 
        $("#alarmedCustomers").css("display", "block"); 
        $("#alarmDates") .css("display", "block");
        $("#alarmBuysDates") .css("display", "none");
        $("#alamButtonsHistoryDiv").css("display", "block");
        $("#noAlarmButtonsHistoryDiv").css("display", "none");
        $(".alarmBtn").css("display", "inline");
        $("#orderUnAlarms").css("display", "none");
    }
});

$("#dirverServiceRadio").on("change", () => {
    $(".driverServicesTable").css("display", "inline");
    $(".bargeriTable").css("display", "none");
    $("#serviceDive").css("display", "inline");
    $("#bottomServiceBttons").css("display", "inline");
    $("#orderService").css("display", "inline");

});
$("#bargeriRadio").on("change", () => {
    $(".driverServicesTable").css("display", "none");
    $(".bargeriTable").css("display", "block");
    $("#serviceDive").css("display", "none");
    $("#bottomServiceBttons").css("display", "none");
    $("#orderService").css("display", "none");
});

$("#employeeType").on("change", function () {
    if ($("#employeeType").val() == 1) {
        $("#saleLineDive").css("display", "inline");
        $("#headDiv").css("display", "none");
        $("#managerDiv").css("display", "none");
        $("#employeeJobDiv").css("display", "none");
    }

    if ($("#employeeType").val() == 2) {
        $("#managerDiv").css("display", "inline");
        $("#saleLineDive").css("display", "none");
        $("#headDiv").css("display", "none");
        $("#employeeJobDiv").css("display", "none");
    }

    if ($("#employeeType").val() == 3) {
        $("#headDiv").css("display", "inline");
        $("#employeeJobDiv").css("display", "inline");
        $("#saleLineDive").css("display", "none");
        $("#managerDiv").css("display", "none");
    }
});

$("#employeeTypeEdit").on("change", function () {
    if ($("#employeeTypeEdit").val() == 1) {
        $("#saleLineDivEdit").css("display", "inline");
        $("#headDivEdit").css("display", "none");
        $("#managerDivEdit").css("display", "none");
        $("#employeeJobDivEdit").css("display", "none");
        $("#managerIdEdit").prop("selected", true);
        $("#headIdEdit").prop("selected", true);
    }

    if ($("#employeeTypeEdit").val() == 2) {
        $("#managerDivEdit").css("display", "inline");
        $("#saleLineDivEdit").css("display", "none");
        $("#headDivEdit").css("display", "none");
        $("#employeeJobDivEdit").css("display", "none");
        $("#headIdEdit").prop("selected", true);
    }

    if ($("#employeeTypeEdit").val() == 3) {
        $("#headDivEdit").css("display", "inline");
        $("#employeeJobDivEdit").css("display", "inline");
        $("#saleLineDivEdit").css("display", "none");
        $("#managerDivEdit").css("display", "none");
        $("#managerIdEdit").prop("selected", true);
    }
});

// R an D script

$("#notLoginRadio").on("change", () => {
    $("#logedIn").css("display", "none");
    $("#notLogin").css("display", "block");
});
$("#logedInRadio").on("change", () => {
    $("#logedIn").css("display", "block");
    $("#notLogin").css("display", "none");
});

function setManagerStuff(element, adminId) {
    $(element).find("input:radio").prop("checked", true);
    let input = $(element).find("input:radio");
    $("#editAdmin").val($(input).val());
    $("#editAdmin").prop("disabled", false);
    $(".caret").css({ color: "gray" });
    $(element).css({ color: "blue" });

    if ($("#takhsisToAdminBtn")) {
        $("#takhsisToAdminBtn").val(adminId);
        $("#takhsisToAdminBtn").prop("disabled", false);
    }

    if ($("#adminTasviyahBtn")) {
        $("#adminTasviyahBtn").val(adminId);
        $("#adminTasviyahBtn").prop("disabled", false);
    }
}

function setHeadStuff(element, headId) {
    $(element).find("input:radio").prop("checked", true);
    let input = $(element).find("input:radio");
    $("#editAdmin").val($(input).val());
    $("#editAdmin").prop("disabled", false);
    $(".caret").css({ color: "gray" });
    $(element).css({ color: "blue" });

    if ($("#adminTasviyahBtn")) {
        $("#adminTasviyahBtn").prop("disabled", false);
    }

    $.ajax({
        method: "get",
        url: baseUrl + "/getEmployees",
        data: { _token: "{{@csrf}}", headId: headId },
        async: true,
        success: function (response) {
            $("#customerListBody").empty();
            response.forEach((element, index) => {
                $("#customerListBody").append(
                    `
                <tr onclick="checkCheckBox(this,event)">
                <td>` + (index + 1) +`</td>
                <td>` + element.name + ` ` + element.lastName +`</td>
                <td>` + element.phone +`</td>
                <td>` +element.discription +`</td>
                <td>
                    <input class="mainGroupId" type="checkbox" name="customerIDs[]" value="` +
                        element.id +
                        `">
                </td>`
                );
            });
        },
        error: function () {},
    });
}
function setHeadOpStuff(element, headId) {
    $(element).find("input:radio").prop("checked", true);
    let input = $(element).find("input:radio");
    $("#editAdmin").val($(input).val());
    $("#editAdmin").prop("disabled", false);
    $(".caret").css({ color: "gray" });
    $(element).css({ color: "blue" });
    if ($("#takhsisToAdminBtn")) {
        $("#takhsisToAdminBtn").val(headId);
    }
    $.ajax({
        method: "get",
        url: baseUrl + "/getEmployees",
        data: { _token: "{{@csrf}}", headId: headId },
        async: true,
        success: function (response) {
            $("#customerListBody").empty();
            response.forEach((element, index) => {
                $("#customerListBody").append(
                    `
                <tr onclick="setKarbarOpStuff(this,` +element.id +`)">
                <td>` +(index + 1) + `</td>
                <td>` + element.name + ` ` +element.lastName + `</td>
                <td>` + element.phone +`</td>
                <td>` + element.discription +`</td>
                <td>
                    <input class="mainGroupId" type="radio" name="customerIDs[]" value="` + element.id +`">
                </td>`
                );
            });
        },
        error: function () {},
    });
}
function setKarbarOpStuff(element, adminId) {
    $(element).find("input:radio").prop("checked", true);
    let input = $(element).find("input:radio");
    $("#takhsisToAdminBtn").val($(input).val());
}
$("#takhsisToAdminBtn").on("click", () => {
    let id = $("#takhsisToAdminBtn").val();
    $.ajax({
        method: "get",
        url: baseUrl + "/getAdminInfo",
        data: {
            _token: "{{ csrf_token() }}",
            id: id,
        },
        async: true,
        success: function (msg) {
            $("#takhsisAdminName").text(msg[3].name + " " + msg[3].lastName);
        },
        error: function (error) {},
    });

    $.ajax({
        method: "get",
        url: baseUrl + "/getCustomer",
        data: {
            _token: "{{ csrf_token() }}",
        },
        async: true,
        success: function (arrayed_result) {
            $("#allCustomer").empty();

            arrayed_result.forEach((element, index) => {
                $("#allCustomer").append(
                    `
                <tr onclick="checkCheckBox(this,event)">
                    <td style="">` +
                        (index + 1) +
                        `</td>
                    <td style="">` +
                        element.PCode +
                        `</td>
                    <td>` +
                        element.Name +
                        `</td>
                    <td style="">
                    <input class="form-check-input" name="customerIDs[]" type="checkbox" value="` +
                        element.PSN +
                        `" id="customerId">
                    </td>
                </tr>
            `
                );
            });
        },
        error: function (data) {},
    });
    $.ajax({
        method: "get",
        url: baseUrl + "/getAddedCustomer",
        data: {
            _token: "{{ csrf_token() }}",
            adminId: id,
        },
        async: true,
        success: function (arrayed_result) {
            $("#addedCustomer").empty();
            arrayed_result.forEach((element, index) => {
                $("#addedCustomer").append(
                    `
                        <tr onclick="checkCheckBox(this,event)">
                            <td id="radif" style="width:55px;">` +
                        (index + 1) +
                        `</td>
                            <td id="mCode" style="width:115px;">` +
                        element.PCode +
                        `</td>
                            <td >` +
                        element.Name +
                        `</td>
                            <td style="width:50px;">
                                <input class="form-check-input" name="addedCustomerIDs[]" type="checkbox" value="` +
                        element.PSN +
                        `" id="kalaId">
                            </td>
                        </tr>
                    `
                );
            });
        },
        error: function (data) {},
    });

    $("#takhsisCustomerModal").modal("show");
});

$("#adminTasviyahBtn").on("click", () => {
    let id = $("#takhsisToAdminBtn").val();
    removeStaff(id);
});

$("#emptyAdminBtn").on("click", () => {
    let id = $("#emptyAdminBtn").val();
    removeStaff(id);
});

$("#moveEmployee").on("click", () => {
    $("#moveEmployeeModal").modal("show");
    $.ajax({
        method: "get",
        url: baseUrl + "/getHeads",
        async: true,
        data: { _token: "{{@csrf}}" },
        success: function (response) {
            $("#headList").empty();
            response.forEach((element, index) => {
                $("#headList").append(
                    `<tr onclick="setHeadSelectStuff(this,` +
                        element.id +
                        `)">
                <td >` +
                        (index + 1) +
                        `</td>
                <td >` +
                        element.name +
                        ` ` +
                        element.lastName +
                        `</td>
                <td >` +
                        element.phone +
                        `</td>
                <td><input class="customerList form-check-input" name="adminId" type="radio" value="` +
                        element.id +
                        `"></td>
            </tr>`
                );
            });
            console.log(response);
        },
        error: function () {},
    });
});

function setHeadSelectStuff(element, headId) {
    $("#moveEmployeeDoneBtn").val(headId);
}

$("#moveEmployeeDoneBtn").on("click", () => {
    var adminID = [];
    $('input[name="customerIDs[]"]:checked').map(function () {
        adminID.push($(this).val());
    });

    $.ajax({
        method: "get",
        url: baseUrl + "/addToHeadEmployee",
        data: {
            _token: "{{@csrf}}",
            adminID: adminID,
            headId: $("#moveEmployeeDoneBtn").val(),
        },
        async: true,
        success: function (response) {
            window.location.reload();
        },
        error: function (error) {},
    });
    alert(customerID.length);
});

function setEmployeeStuff(element) {
    $(element).find("input:radio").prop("checked", true);
    let input = $(element).find("input:radio");
    $("#editAdmin").val($(input).val());
    $("#editAdmin").prop("disabled", false);
    $(".caret").css({ color: "gray" });
    $(element).css({ color: "blue" });
}

// تنظیمات
//صفحه تخصیص جدید
$("#takhsisManagerRadio").on("change", () => {
    $.ajax({
        method: "get",
        url: baseUrl + "/getEmployies",
        data: {
            _token: "{{@csrf}}",
            employeeType: $("#takhsisManagerRadio").val(),
        },
        async: true,
        success: function (respond) {
            $("#adminGroupList").empty();
            respond.forEach((element, index) => {
                let countCustomer = "0";
                let takhsisDate = "مشتری ندارد";
                if (element.countCustomer) {
                    countCustomer = element.countCustomer;
                }
                if (element.takhsisDate) {
                    takhsisDate = element.takhsisDate;
                }
                $("#adminGroupList").append(
                    `
                    <tr onclick="setAdminStuff(this)">
                        <td>` +
                        (index + 1) +
                        `</td>
                        <td>` +
                        element.name +
                        ` ` +
                        element.lastName +
                        `</td>
                        <td> ` +
                        countCustomer +
                        ` </td>
                        <td> ` +
                        takhsisDate +
                        ` </td>
                        <td> <input class="mainGroupId" type="radio" name="AdminId[]" value="` +
                        element.id +
                        ` ` +
                        element.adminTypeId +
                        `"> </td>
                    </tr>`
                );
            });
        },
        error: function (error) {},
    });
});

$("#takhsisHeadRadio").on("change", () => {
    $.ajax({
        method: "get",
        url: baseUrl + "/getEmployies",
        data: {
            _token: "{{@csrf}}",
            employeeType: $("#takhsisHeadRadio").val(),
        },
        async: true,
        success: function (respond) {
            $("#adminGroupList").empty();
            respond.forEach((element, index) => {
                let countCustomer = "0";
                let takhsisDate = "مشتری ندارد";
                if (element.countCustomer) {
                    countCustomer = element.countCustomer;
                }
                if (element.takhsisDate) {
                    takhsisDate = element.takhsisDate;
                }
                $("#adminGroupList").append(
                    `
                    <tr onclick="setAdminStuff(this)">
                        <td>` +
                        (index + 1) +
                        `</td>
                        <td>` +
                        element.name +
                        ` ` +
                        element.lastName +
                        `</td>
                        <td> ` +
                        countCustomer +
                        ` </td>
                        <td> ` +
                        takhsisDate +
                        ` </td>
                        <td> <input class="mainGroupId" type="radio" name="AdminId[]" value="` +
                        element.id +
                        ` ` +
                        element.adminTypeId +
                        `"> </td>
                    </tr>`
                );
            });
        },
        error: function (error) {},
    });
});

$("#takhsisEmployeeRadio").on("change", () => {
    $.ajax({
        method: "get",
        url: baseUrl + "/getEmployies",
        data: {
            _token: "{{@csrf}}",
            employeeType: $("#takhsisEmployeeRadio").val(),
        },
        async: true,
        success: function (respond) {
            $("#adminGroupList").empty();
            respond.forEach((element, index) => {
                let countCustomer = "0";
                let takhsisDate = "مشتری ندارد";
                if (element.countCustomer) {
                    countCustomer = element.countCustomer;
                }
                if (element.takhsisDate) {
                    takhsisDate = element.takhsisDate;
                }
                $("#adminGroupList").append(
                    `
                    <tr onclick="setAdminStuff(this)">
                        <td>` +
                        (index + 1) +
                        `</td>
                        <td>` +
                        element.name +
                        ` ` +
                        element.lastName +
                        `</td>
                        <td> ` +
                        countCustomer +
                        ` </td>
                        <td> ` +
                        takhsisDate +
                        ` </td>
                        <td> <input class="mainGroupId" type="radio" name="AdminId[]" value="` +
                        element.id +
                        ` ` +
                        element.adminTypeId +
                        `"> </td>
                    </tr>`
                );
            });
        },
        error: function (error) {},
    });
});
//

$(document).on("click", "#loadMore", () => {
    $(".showLater").css("display", "block");
});

function openAddCommentModal(customerId) {
    $("#customerIdForComment").val(customerId);
    $("#viewComment").modal("show");
}





$("#getPersonalsForm").on("submit",function(e){
e.preventDefault();

$.ajax({
    url: $(this).attr("action"),
    data: $(this).serialize(),
    success: function (data) {
        $("#adminList").empty();
        data.forEach((element,index)=>{
            let adminType = "";

            switch (element.adminType) {
                case `1`:
                    adminType="ادمین"
                    break;
                case `2`:
                    adminType="پشتیبان"
                    break;
                case `3`:
                    adminType="بازاریاب"
                    break;
                case `4`:
                    adminType="راننده"
                    break;
                case `5`:
                    adminType="مدیر سیستم"
                    break;
                case `6`:
                    adminType="مدیر"
                    break;
                case `7`:
                    adminType="سرپرست"
                    break;
            }
            $("#adminList").append(`
                <tr onclick="setAdminStuffForAdmin(this,`+element.adminType+`,`+element.driverId+`)">
                    <td>`+(index+1)+`</td>
                    <td>`+element.name+` `+element.lastName+`</td>
                    <td>`+adminType+`</td>
                    <td class="descriptionForMobile">`+element.discription+`</td>
                    <td>
                        <input class="mainGroupId" type="radio" name="AdminId[]" value="`+element.id+`_`+element.adminType+`">
                    </td>
                </tr>`);
        });
    },
    error:function(error){}
});
});



$("#searchManagerByLine").on("change",function(){
$.ajax({method:"get",
        url:baseUrl+"/getManagerByLine",
        data:{
            _token:"{{@crsf}}",
            lineId:$("#searchManagerByLine").val()
        },
        async:true,
        success:function(data){
            $("#searchManagerSelect").empty();
            $("#searchManagerSelect").append(`<option value="-1">مدیران</option>`)
            data.forEach((element)=>{
                $("#searchManagerSelect").append(`<option value="`+element.id+`">`+element.name+` `+element.lastName+`</option>`)
            });

        },
        error:function(error){
            console.log(error)
        }
})
})






$("#searchManagerSelect").on("change",()=>{
   
    $.ajax({method:"get",
        url:baseUrl+'/getOrgChart',
        data:{_token:"{{@csrf}}",
              managerId:$("#searchManagerSelect").val()},
        async:true,
        success:function(respons){
            console.log(respons)
              
// Create root and chart for oganizational chart
    var root = am5.Root.new("chartdiv12");
        root._logo.dispose();
    
        root.setThemes([
             am5themes_Animated.new(root)
        ]);

        var data = respons;

        var container = root.container.children.push(
            am5.Container.new(root, {
                width: am5.percent(100),
                height: am5.percent(100),
                layout: root.verticalLayout
            })
        );

        var series = container.children.push(
            am5hierarchy.Tree.new(root, {
                singleBranchOnly: false,
                downDepth:1,
                initialDepth: 5,
                topDepth: 0,
                valueField: "value",
                categoryField: "name",
                childDataField: "children",
                idField: "idField",
                linkWithField: "link"
            })
        );

        series.circles.template.setAll({
                radius:38,
        });

        series.outerCircles.template.setAll({
               radius:39
        });

        series.labels.template.setAll({
              fontSize: 30,
         });


        series.circles.template.events.on("click", function(ev) {
           var nextUrl = ev.target.dataItem.dataContext.idField;
           var url;
           $.ajax({method:"get",
                    url:baseUrl+'/getEmployeeInfo',
                    data:{_token:"{{@csrf}}",
                            adminId:nextUrl},
                        async:true,
                    success:function(respond){
                        if(respond.adminType==2 || respond.adminType==4){
                            if(respond.adminType==4){
                                url = baseUrl + "/poshtibanActionInfo?subPoshtibanId="+respond.driverId;
                                window.open(url);
                            }else{
                                url = baseUrl + "/poshtibanActionInfo?subPoshtibanId="+nextUrl;
                                window.open(url);
                            }
                        }else{
                            url = baseUrl + "/saleExpertActionInfo?subId="+nextUrl;
                            window.open(url);
                        }
                    },
                    error:function(error){
                    }
                });
           });
        series.data.setAll(data);
        series.set("selectedDataItem", series.dataItems[0]);
      },
        error:function(error){
        
       }
    })
});




$(document).on("change",".headsRadio",function(){
    alert($(this).val())
    $.ajax({method:"get",
        url:baseUrl+'/getAdminInfo',
        data:{_token:"{{@csrf}}",
            id:$(this).val()},
            async:true,
        success:function(data){
            console.log(data[4])
            $("#bazaryabList").empty();
            data[4].forEach((element,index)=>{
                $("#bazaryabList").append(`
                <div class="form-check bg-gray">
                    <input class="personalList form-check-input p-2 float-end" type="radio" name="settings" value="`+element.id+`">
                    <label class="form-check-label me-4" for="assesPast">`+element.name+` `+element.lastName+`</label>
                </div>
                `);
            })

        }
        ,
        error:function(error){

        }
    });
});



// kala

$(document).on("change",".personalList",function(){
    $.ajax({
        method:"get",
        url:baseUrl+'/getCustomers',
        data:{_token:"{{@csrf}}",
              adminId:$(this).val()
            },
        success:function(data){
            $("#customerListBody").empty();
            data.forEach((element,index)=>{
                visitDate="";
                if(element.lastVisitDate==null){
                    visitDate="ورود ندارد"
                }else{
                    visitDate=moment(element.lastVisitDate, 'YYYY/M/D HH:mm:ss').locale('fa').format('YYYY/M/D');
                }
                $("#customerListBody").append(`
                <tr onclick="getCustomerInfo(this,`+element.PSN+`)">
                    <td>`+(index+1)+`</td>
                    <td>`+element.Name+`</td>
                    <td>`+element.FactDate+`</td>
                    <td>`+visitDate+`</td>
                    <td>
                        <input class="mainGroupId" type="radio" name="AdminId[]" value="">
                    </td>
                </tr>`);
            })
        },
        error:function(error){

        }
    })
});

function getCustomerInfo(element,customerId) {
    $("tr").removeClass("selected");
    $(element).toggleClass("selected");
    $.ajax({
        method: "get",
        url: baseUrl + "/getCustomerInfo",
        data: {
            _token: "{{ csrf_token() }}",
            csn: customerId,
        },
        async: true,
        success: function (data) {
            $("#customerSpecialComment").text("");
            $("#customerSpecialComment").text(data[0].comment);
        },
        error:function(error){}
    });
}
function getKalaId(element){
    $(element).find("input:radio").prop("checked", true);
   let input = $(element).find("input:radio");
   var kalaId =  input.val();
   $("#kalaSettingsBtn").val(kalaId);
   $("#kalaSettingsBtn").prop("disabled", false);

}


// kala settings script 
 $("#kalaSettingsBtn").on("click", () => {

     const kalaId = $("#kalaSettingsBtn").val();
     $("#kalaIdForAddStock").val(kalaId);
     $("#kalaIdSpecialRest").val(kalaId);
     $("#kalaIdEdit").val(kalaId);
     $("#kalaIdDescription").val(kalaId);
     $("#kalaIdSameKala").val(kalaId);
     $('#mainPicEdit').attr('src',baseUrl+'/resources/assets/images/kala/'+kalaId+'_1.jpg');
     $("#kalaIdChangePic").val(kalaId);
     

    $.ajax({
        method: "get",
        dataType: "json",
        url: baseUrl+"/kalaSettings",
        data: {
            _token: "{{ csrf_token() }}",
            kalaId: kalaId
        },
        async: true,
        success: function(data) {
            let kala = data[0];
            let maingroupList = data[1];
            let stocks = data[2];
            let sameKala = data[3];
            let addedStocks = data[4];
            let costInfo = data[5];
            let kalaPriceCycle = data[6];
            $("#original").text(kala.NameGRP);
            $("#editKalaTitle").text("ویرایش :  " +"  "+kala.GoodName);
            $("#subsidiary").text(kala.NameGRP);
            $("#mainPrice").text(kala.mainPrice);
            $("#overLinePrice").text(kala.overLinePrice);
            $("#costLimit").val(kala.costLimit);
            $("#costContent").val(kala.costError);
            $("#costAmount").val(kala.costAmount);
            $("#existanceAlarm").val(kala.alarmAmount);
            $("#descriptionKala").text(kala.descProduct);
            $("#minSaleValue").text(kala.minSale +" "+ kala.secondUnit +" " + " تعیین شده است ");
            $("#maxSaleValue").text(kala.maxSale +" "+ kala.secondUnit +" " + " تعیین شده است ");

            
            $("#maingroupTableBody").empty();
             maingroupList.forEach((element, index)=> {
                $("#maingroupTableBody").append(`
                     <tr id="grouptableRow">
                        <td>`+(index+1)+`</td>
                        <td>`+element.title+`</td>
                        <td><input type="checkBox" class="form-check-input" disabled `+ (element.exist === 'ok' ? 'checked' : 'unchecked')+` ></td>
                        <td>
                            <input class="mainGroupId form-check-input" type="radio" value="`+element.id+`_` +kala.GoodSn+ `" name="IDs[]" id="flexCheckChecked">
                            <input class="mainGroupId" type="text" value="`+kala.GoodSn+`" name="ProductId" id="GoodSn" style="display: none">
                        </td>
                    </tr>`
             );


           $("#costTypeInfo").empty();
            costInfo.forEach((element, index)=> {
            $("#costTypeInfo").append(`
                <option `+(kala.inforsType==element.SnInfor ? "selected" : " ")+` value="`+element.SnInfor+`">`+element.InforName+`</option> 
            `)
            });

// while check takhsis Anbar Checkbox it will append to the bottome table 
           $("#allStockForList").empty();
           stocks.forEach((element, index)=> {
            $("#allStockForList").append(`
                    <tr>
                        <td>`+(index+1)+`</td>
                        <td>`+element.NameStock+`</td>
                        <td>
                            <input class="form-check-input" name="stock[]" type="checkbox" value="`+element.SnStock+'_'+element.NameStock+`" id="stockId">
                        </td>
                    </tr>
                 `)
            });


            $(document).on('click', '#removeStocksFromWeb', (function() {
                    $('tr').find('input:checkbox:checked').attr("name", "removeStocksFromWeb[]");
                    $('tr').has('input:checkbox:checked').hide();
                 }));
        
        
            $(document).on('click', '#addStockToWeb', (function() {
                    var kalaListID = [];
                    $('input[name="allStocks[]"]:checked').map(function() {
                    kalaListID.push($(this).val());
                });
        
                $('input[name="allStocks[]"]:checked').parents('tr').css('color', 'white');
                $('input[name="allStocks[]"]:checked').parents('tr').children('td').css('background-color', 'red');
                $('input[name="allStocks[]"]:checked').prop("disabled", true);
                $('input[name="allStocks[]"]:checked').prop("checked", false);
        
            for (let i = 0; i < kalaListID.length; i++) {
                $('#addedStocks').prepend(`
                    <tr class="addedTrStocks" onclick="checkCheckBox(this,event)">
                        <td>` + kalaListID[i].split('_')[0] + `</td>
                        <td>` + kalaListID[i].split('_')[1] + `</td>
                        <td>
                            <input class="form-check-input" name="addedStocksToWeb[]" type="checkbox" value="` + kalaListID[i].split('_')[0] + `_` + kalaListID[i].split('_')[1] + `" id="kalaIds" checked>
                        </td>
                    </tr>`);
                    }
                }));



// the following code assign Anbar to the left table 
           $("#allstockOfList").empty();
           addedStocks.forEach((element,index)=> {
            $("#allstockOfList").append(`
                <tr onclick="checkCheckBox(this)">
                    <td>`+(index+1)+`</td>
                    <td>`+element.NameStock+`</td>
                    <td>
                    <input  class="addStockToList form-check-input" name="addedStockToList[]" type="checkbox" value="`+element.SnStock+`">
                    </td>
                </tr>
              `)
            });

 //for setting minimam saling of kala
            $(document).on('click', '.setMinSale', (function() {
                var amountUnit = $(this).val().split('_')[0];
                var productId = $(this).val().split('_')[1];
            $.ajax({
                type: "get",
                url: baseUrl+"/setMinimamSaleKala",
                data: { _token: "{{ csrf_token() }}", kalaId: productId, amountUnit: amountUnit },
                dataType: "json",
            success: function(msg) {
            $("#minSaleValue").text(msg +" "+ kala.secondUnit +" " + " تعیین شده است ");
            },
            error: function(msg) {
            console.log(msg);
            }
            });
            }));



//for setting maximam saling of kala
        $(document).on('click', '.setMaxSale', (function() {
            var amountUnit = $(this).val().split('_')[0];
            var productId = $(this).val().split('_')[1];
        $.ajax({
            type: "get",
            url: baseUrl+"/setMaximamSaleKala",
            data: { _token: "{{ csrf_token() }}", kalaId: productId, amountUnit: amountUnit },
            dataType: "json",
        success: function(msg) {
            $("#maxSaleValue").text(msg +" "+ kala.secondUnit +" " + " تعیین شده است ");
        },
        error: function(msg) {
            console.log(msg);
        }
        });
        }));



    
    
$(document).on("click", "#submitSubGroup", () => {
    var addableStuff = [];
    let kalaId = $("#kalaIdEdit").val();
    $('input[name="addables[]"]:checked').map(function() {
        addableStuff.push($(this).val());
        });
        var removableStuff = [];
        $('input[name="removables[]"]:not(:checked)').map(function() {
        removableStuff.push($(this).val());
        });
        $.ajax({
        type: "get",
        url: baseUrl + "/addOrDeleteKalaFromSubGroup",
        data: {
            _token: "{{ csrf_token() }}",
            addableStuff: addableStuff,
            removableStuff: removableStuff,
            kalaId: kalaId
        },
        dataType: "json",
        success: function(msg) {
            $('#submitSubGroup').prop("disabled", true);
            $("#stockSubmit").css("display","none");
            $("#kalaRestictionbtn").css("display","none");
            $("#completDescriptionbtn").css("display","none");
            $("#addToListSubmit").css("display","none");
            $("#submitChangePic").css("display","none");
        },
        error: function(msg) {
            console.log(msg);
        }
        });
        });


    
    // following function show the kala restriction button
            $(".restriction").on("click", ()=> {
                $("#kalaRestictionbtn").css("display", "block");
                $("#stockSubmit").css("display", "none");
                $("#completDescriptionbtn").css("display","none");
                $("#addToListSubmit").css("display","none");
                $("#submitChangePic").css("display","none");
                $("#submitSubGroup").css("display", "none");
            });

    // following function show the kala restriction button
            $(".keyRestriction").on("keydown", ()=> {
                $("#kalaRestictionbtn").css("display", "block");
                $("#stockSubmit").css("display", "none");
                $("#completDescriptionbtn").css("display","none");
                $("#addToListSubmit").css("display","none");
                $("#submitChangePic").css("display","none");
                $("#submitSubGroup").css("display", "none");
            });


// for added sameKala 
            $("#allKalaOfList").empty();
            sameKala.forEach((element, index)=> {
                $("#allKalaOfList").append(`
                      <tr class="addedTrList">
                            <td>`+(index+1)+`</td>
                            <td>`+element.GoodName+`</td>
                            <td>
                            <input class="form-check-input" style="" name="" type="checkbox" value="`+element.GoodSn+'_'+element.GoodName+`" id="kalaIds">
                            </td>
                      </tr>
                  `)
                });


            $("#priceCycle").empty();
              kalaPriceCycle.forEach((element, index)=> {
                $("#priceCycle").append(`
                     <tr class="tableRow">
                        <td>`+(index+1)+`</td>
                        <td>`+ element.name+' '+element.lastName+`</td>
                        <td>`+element.application+`</td>
                        <td>`+moment(element.changedate, 'YYYY/M/D HH:mm:ss').locale('fa').format('YYYY/M/D') + `</td>
                        <td>`+element.firstPrice+`</td>
                        <td>`+element.changedFirstPrice+`</td>
                        <td>`+element.secondPrice+`</td>
                        <td>`+element.changedSecondPrice+`</td>
                        <td>
                            <input class="mainGroupId  form-check-input" type="radio" value="`+ maingroupList.id + '_' +kala.GoodSn+`" name="IDs[]" id="flexCheckChecked">
                            <input class="mainGroupId" type="text" value="`+ kala.GoodSn+`" name="ProductId" id="GoodSn" style="display: none">
                        </td>
                    </tr>
                `)
            });

        
        $(".kalaEditbtn").on("click", ()=> {
            $("#submitChangePic").css("display", "block");
            $("#stockSubmit").css("display", "none");
            $('#completDescriptionbtn').css('display', 'none');
            $("#kalaRestictionbtn").css("display", "none");
            $("#addToListSubmit").css("display","none");
            $("#submitSubGroup").css("display", "none");
        });

      
   

// chech or uncheck the kala restriction 
            if(kala.callOnSale==1){
                $('#callOnSale').prop('checked', true);
            }else{
                $('#callOnSale').prop('checked', false);
            }

            if(kala.zeroExistance==1){
                $('#zeroExistance').prop('checked', true);
            }else{
                $('#zeroExistance').prop('checked', false);
            }

            if(kala.showTakhfifPercent==1){
                $('#showTakhfifPercent').prop('checked', true);
            }else{
                $('#showTakhfifPercent').prop('checked', false);
            }

            if(kala.overLine==1){
                $('#showFirstPrice').prop('checked', true);
            }else{
                $('#showFirstPrice').prop('checked', false);
            }

            if(kala.hideKala==1){
                $('#inactiveAll').prop('checked', true);
            }else{
                $('#inactiveAll').prop('checked', false);
            }

            if(kala.freeExistance==1){
                $('#freeExistance').prop('checked', true);
            }else{
                $('#freeExistance').prop('checked', false);
            }

            if(kala.activePishKharid==1){
                $('#activePreBuy').prop('checked', true);
            }else{
                $('#activePreBuy').prop('checked', false);
            }

               
  });

  
 
       
// while onclick on radio button adding subgroup to left table 
            $(".mainGroupId").on("click", ()=> {
                $.ajax({
                type: 'get',
                async: true,
                dataType: 'text',
                url: baseUrl + "/subGroupsEdit",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: $('.mainGroupId:checked').val().split('_')[0],
                    kalaId: $('.mainGroupId:checked').val().split('_')[1]
                },
                success: function(answer) {
                    data = $.parseJSON(answer);
                    $('#subGroup1').empty();
                    for (var i = 0; i <= data.length - 1; i++) {
                        $('#subGroup1').append(
                            `<tr id="subgroupTableRow" onClick="addOrDeleteKala(this)">
                                <td>` + (i + 1) + `</td>
                                <td>` + data[i].title + `</td>
                                <td>
                                   <input class="subGroupId form-check-input" name="subGroupId[]" value="` + data[i].id + `_` + data[i].selfGroupId + `" type="checkBox" id="flexCheckChecked` + i + `">
                               </td>
                        </tr>`);
                        if (data[i].exist == 'ok') {
                            $('#flexCheckChecked' + i).prop('checked', true);
                        } else {
                            $('#flexCheckChecked' + i).prop('checked', false);
                        }
                    }
                }
                });
                });

                $("#groupSubgoupCategory").on("submit", function(e) {
                    var addableStuff = [];
                    let kalaId = $("#kalaIdEdit").val();

                   $('input[name="addables[]"]:checked').map(function() {
                     addableStuff.push($(this).val());
                  });
               
                 var removableStuff = [];
                   $('input[name="removables[]"]:not(:checked)').map(function() {
                        removableStuff.push($(this).val());
                  });
                    $.ajax({
                    type: "get",
                    url: baseUrl+"/addOrDeleteKalaFromSubGroup",
                    data: {
                        _token: "{{ csrf_token() }}",
                        addables: addableStuff,
                        removables: removableStuff,
                        ProductId: kalaId
                    },
                    dataType: "json",
                    success: function(msg) {
                            console.log(msg);
                        $('#submitSubGroup').prop("disabled", true);
                    },
                    error: function(msg) {
                        console.log(msg);
                    }
                    });
                    e.preventDefault();
               });
               

            $("#stockTakhsis").change(() => {
                if ($("#stockTakhsis").is(":checked")) {
                    $("#allStock").css("display", "flex");
                    $("#addAndDeleteStock").css("display", "flex");
                    $("#stockSubmit").css("display", "block");
                    $("#submitSubGroup").css("display", "none");
                    $("#kalaRestictionbtn").css("display","none");
                    $("#completDescriptionbtn").css("display","none");
                    $("#addToListSubmit").css("display","none");
                    $("#submitChangePic").css("display","none");
                } else {
                    $("#stockSubmit").css("display", "none");
                    $("#kalaRestictionbtn").css("display","none");
                    $("#completDescriptionbtn").css("display","none");
                    $("#addToListSubmit").css("display","none");
                    $("#submitChangePic").css("display","none");
                    $("#submitSubGroup").css("display", "block");
                }
                });

             if (!($('.modal.in').length)) {
                $('.modal-dialog').css({
                  top: 0,
                  left: 0
                });
              }
              $('#kalaSettingModal').modal({
                backdrop: false,
                show: true
              });
              
              $('.modal-dialog').draggable({
                  handle: ".modal-header"
                });

         $("#kalaSettingModal").modal("show");
        },


        error: function(data) {
            alert("Some thing went to wrong in editing kala modal");
        }
    });
    
    });


function SetMinQty() {
    const code=$("#kalaIdEdit").val();
$.ajax({
type: "get",
url: baseUrl + "/getUnitsForSettingMinSale",
data: { _token: "{{ csrf_token() }}", Pcode: code },
dataType: "json",
success: function(msg) {
    $("#unitStuffContainer").html(msg);
    const modal = document.querySelector('.modalBackdrop');
    const modalContent = modal.querySelector('.modal');
    modal.classList.add('active');
    modal.addEventListener('click', () => {
        modal.classList.remove('active');
    });
},
error: function(msg) {
    alert('Not good');
    console.log(msg);
}
});
}
    
function SetMaxQty() {
    const code=$("#kalaIdEdit").val();
        $.ajax({
        type: "get",
        url: baseUrl + "/getUnitsForSettingMaxSale",
        data: { _token: "{{ csrf_token() }}", Pcode: code },
        dataType: "json",
        success: function(msg) {
            $("#unitStuffContainer").html(msg);
            const modal = document.querySelector('.modalBackdrop');
            const modalContent = modal.querySelector('.modal');
            modal.classList.add('active');
            modal.addEventListener('click', () => {
                modal.classList.remove('active');
            });

        },
    error: function(msg) {
        alert('Not good');
        console.log(msg);
    }
    });
}

function UpdateQty(code, event, SnOrderBYS) {
        $.ajax({
        type: "get",
        url: baseUrl + "/getUnitsForUpdate",
        data: {
            _token: "{{ csrf_token() }}",
            Pcode: code
        },
        dataType: "json",
        success: function(msg) {
            $("#unitStuffContainer").html(msg);
            $(".SnOrderBYS").val(SnOrderBYS);
            const modal = document.querySelector('.modalBackdrop');
            const modalContent = modal.querySelector('.modal');
            modal.classList.add('active');
            modal.addEventListener('click', () => {
                modal.classList.remove('active');
            });

   
        },
        error: function(msg) {
            console.log(msg);
        }
    });
}





function activeSubmitButton(element) {

if (element.id == "callOnSale") {
if (element.checked) {
    document.querySelector("#zeroExistance").checked = false;
    document.querySelector("#showTakhfifPercent").checked = false;
    document.querySelector("#showFirstPrice").checked = false;
    document.querySelector("#freeExistance").checked = false;
    document.querySelector("#activePreBuy").checked = false;
} else {}
}
if (element.id == "inactiveAll") {
if (element.checked) {
    document.querySelector("#zeroExistance").checked = false;
    document.querySelector("#showTakhfifPercent").checked = false;
    document.querySelector("#showFirstPrice").checked = false;
    document.querySelector("#freeExistance").checked = false;
    document.querySelector("#activePreBuy").checked = false;
    document.querySelector("#callOnSale").checked = false;
} else {}
}
if (element.id == "zeroExistance") {
if (element.checked) {
    document.querySelector("#callOnSale").checked = false;
    document.querySelector("#showTakhfifPercent").checked = false;
    document.querySelector("#showFirstPrice").checked = false;
    document.querySelector("#freeExistance").checked = false;
    document.querySelector("#activePreBuy").checked = false;
} else {}
}
if (element.id == "showTakhfifPercent") {
if (element.checked) {
    document.querySelector("#zeroExistance").checked = false;
    document.querySelector("#callOnSale").checked = false;
} else {}
}

if (element.id == "showFirstPrice") {
if (element.checked) {
    document.querySelector("#callOnSale").checked = false;
    document.querySelector("#zeroExistance").checked = false;
} else {}
}
if (element.id == "freeExistance") {
if (element.checked) {
    document.querySelector("#callOnSale").checked = false;
    document.querySelector("#zeroExistance").checked = false;
} else {}
}

if (element.id == "activePreBuy") {
if (element.checked) {
    document.querySelector("#callOnSale").checked = false;
    document.querySelector("#zeroExistance").checked = false;
} else {
    //do nothing
}
}
$("#restrictStuffId").prop("disabled",false);
}


//برای افزودن انبار به لیست دست چپ
        $(document).on('click', '#addStockToList', (function() {
            var stockListID = [];
            $('input[name="stock[]"]:checked').map(function() {
                stockListID.push($(this).val());
            });

            $("#stockSubmit").prop("disabled", false);
            $('input[name="stock[]"]:checked').parents('tr').css('color', 'white');
            $('input[name="stock[]"]:checked').parents('tr').children('td').css('background-color', 'red');
            $('input[name="stock[]"]:checked').prop("disabled", true);
            $('input[name="stock[]"]:checked').prop("checked", false);
            for (let i = 0; i < stockListID.length; i++) {
            $('#allstockOfList').append(`
                    <tr>
                        <td>` + (i + 1) + `</td>
                        <td>` + stockListID[i].split('_')[1] + `</td>
                        <td>
                             <input class="addStockToList form-check-input" name="addedStockToList[]" type="checkbox" value="` + stockListID[i].split('_')[0] + `" id="kalaIds" checked>
                        </td>
                    </tr>
                    `);
                }
            }));


//حذف انبار
$(document).on('click', '#removeStockFromList', (function() {
    $('tr').find('input:checkbox:checked').attr("name", "removeStockFromList[]");
    $('tr').has('input:checkbox:checked').hide();
    $("#stockSubmit").prop("disabled", false);
    $('#completDescriptionbtn').css('display', 'none');
    $("#kalaRestictionbtn").css("display", "none");
    $("#addToListSubmit").css("display","none");
    $("#submitChangePic").css("display","none");
    $("#submitSubGroup").css("display", "none");
   }
  )
 );

$("#sameKalaList").change(function() {
if ($("#sameKalaList").is(':checked')) {
$("#addKalaToList").css("display", "flex");
$("#addAndDelete").css("display", "flex");
$("#addToListSubmit").css("display", "flex");
$("#addedList").css("display", "flex");
let mainKalaId = $("#mainKalaId").val();
$.ajax({
    method: 'get',
    url: baseUrl + "/getAllKalas",
    data: { _token: "{{ csrf_token() }}", mainKalaId: mainKalaId },
    dataType: "json",
    async: true,
    success: function(arrayed_result) {
        $('#allKalaForList').empty();
        for (var i = 0; i <= arrayed_result.length - 1; i++) {
            $('#allKalaForList').append(`
        <tr  onclick="checkCheckBox(this,event)">
            <td>` + (i + 1) + `</td>
            <td>` + arrayed_result[i].GoodName + `</td>
            <td>
            <input class="form-check-input" name="kalaListForList[]" type="checkbox" value="` +
                arrayed_result[i].GoodSn + `_` + arrayed_result[i]
                .GoodName + `" id="kalaId">
            </td>
        </tr>
        `);
        }
    },
    error: function(data) {}
});
} else {
$("#addKalaToList").css("display", "none");
$("#addAndDelete").css("display", "none");
$("#addToListSubmit").css("display", "none");
}
});
$('#mainPic').on('change', () => {
$("#submitChangePic").prop('disabled', false);
}

);


//used for adding kala to List to the left side(kalaList)
$(document).on('click', '#addDataToList', (function() {

var kalaListID = [];
$('input[name="kalaListForList[]"]:checked').map(function() {
kalaListID.push($(this).val());
});
$("#addToListSubmit").prop("disabled", false);
$('input[name="kalaListForList[]"]:checked').parents('tr').css('color', 'white');
$('input[name="kalaListForList[]"]:checked').parents('tr').children('td').css('background-color', 'red');
$('input[name="kalaListForList[]"]:checked').prop("disabled", true);
$('input[name="kalaListForList[]"]:checked').prop("checked", false);
for (let i = 0; i < kalaListID.length; i++) {
$('#allKalaOfList').append(`<tr class="addedTrList">
<td>` + (i + 1) + `</td>
<td>` + kalaListID[i].split('_')[1] + `</td>
<td>
<input class="addKalaToList form-check-input" name="addedKalaToList[]" type="checkbox" value="` + kalaListID[i].split('_')[0] + `_` + kalaListID[i].split('_')[1] + `" id="kalaIds" checked>
</td>
</tr>`);

}
}));


// for submiting Samekala form 
$('#sameKalaForm').on('submit', function(e) {

    $.ajax({
        url: $(this).attr('action'),
        type: $(this).attr('method'),
        dataType: 'json',
        data: $(this).serialize(),
        success: function(data) {
			console.log(data);
            $("#stockSubmit").css("display", "none");
            $('#completDescriptionbtn').css('display', 'none');
            $("#kalaRestictionbtn").css("display", "none");
            $("#addToListSubmit").css("display","none");
            $("#submitSubGroup").css("display", "block");

            
            $("#addKalaToList").css("display", "none");
            $("#addAndDelete").css("display", "none");
            $("#sameKalaList").prop("checked",false);
        },

        error: function(xhr, err) {
            alert('same Kala is not submited');
        }
        
    });
	
	    e.preventDefault();
    
});




//used for removing data from assame List
$(document).on('click', '#removeDataFromList', (function() {
$('tr').find('input:checkbox:checked').attr("name", "removeKalaFromList[]");
$('tr').has('input:checkbox:checked').hide();
}));




function addOrDeleteKala(element) {
    let input = $(element).find('input:checkbox');
        if (input.is(":checked")) {
            input.prop("checked", false);
            input.prop("name", 'removables[]');
            $("#submitSubGroup").prop("disabled",false);
        }else{
            input.prop("checked", true);
            input.prop("name", 'addables[]');
            $("#submitSubGroup").prop("disabled",false);
    }
    }
$(document).on("click", "#submitSubGroup", () => {
var addableStuff = [];
let kalaId = $("#kalaIdEdit").val();
$('input[name="addables[]"]:checked').map(function() {
addableStuff.push($(this).val());
});
var removableStuff = [];
$('input[name="removables[]"]:not(:checked)').map(function() {
removableStuff.push($(this).val());
});
$.ajax({
type: "get",
url: baseUrl + "/addOrDeleteKalaFromSubGroup",
data: {
    _token: "{{ csrf_token() }}",
    addableStuff: addableStuff,
    removableStuff: removableStuff,
    kalaId: kalaId
},
dataType: "json",
success: function(msg) {
    $('#submitSubGroup').prop("disabled", true);
},
error: function(msg) {
    console.log(msg);
}
});
});

$(document).on("submit", "#addDescKala", () => {

$.ajax({
url: $(this).attr('action'),
type: $(this).attr('method'),
dataType: 'json',
data: $(this).serialize(),
success: function(data) {},
error: function(xhr, err) {
    alert('Error');
}
});
return false;
});



 //سبمیت محدودیت ها روی کالا
$("#restrictFormStuff").on('submit', function(event) {
   
    event.preventDefault();
    if (!($("#inactiveAll").is(':checked'))) {
        
        let inputElements = document.getElementsByTagName('input');
        let len = inputElements.length;

        for (let i = 0; i < len; i++) {
            inputElements[i].disabled = false;
        }

        let buttonElements = document.getElementsByTagName('button');
        let buttonLen = buttonElements.length;
        for (let i = 0; i < buttonLen; i++) {
            buttonElements[i].disabled = false;
        }
        let selectElements = document.getElementsByTagName('select');
        let selectLen = selectElements.length;

        for (let i = 0; i < selectLen; i++) {
            selectElements[i].disabled = false;
        }
        let textAreaElements = document.getElementsByTagName('textArea');
        let textAreaLen = textAreaElements.length;

        for (let i = 0; i < textAreaLen; i++) {
            textAreaElements[i].disabled = false;
        }

    } else {
        document.querySelector("#zeroExistance").checked = false;
        document.querySelector("#showTakhfifPercent").checked = false;
        document.querySelector("#showFirstPrice").checked = false;
        document.querySelector("#freeExistance").checked = false;
        document.querySelector("#activePreBuy").checked = false;
    }

$.ajax({
    url: $(this).attr('action'),
    type: $(this).attr('method'),
    dataType: 'json',
    data: $(this).serialize(),

success: function(data) {
    if (data == 1) {
        
        let inputElements = document.getElementsByTagName('input');
        let len = inputElements.length;

        for (let i = 0; i < len; i++) {
            inputElements[i].disabled = true;
        }
        let buttonElements = document.getElementsByTagName('button');
        let buttonLen = buttonElements.length;

        for (let i = 0; i < buttonLen; i++) {
            buttonElements[i].disabled = true;
        }
        let selectElements = document.getElementsByTagName('select');
        let selectLen = selectElements.length;

        for (let i = 0; i < selectLen; i++) {
            selectElements[i].disabled = true;
        }
        let textAreaElements = document.getElementsByTagName('textArea');
        let textAreaLen = textAreaElements.length;

        for (let i = 0; i < textAreaLen; i++) {
            textAreaElements[i].disabled = true;
        }
        document.querySelector("#inactiveAll").disabled = false;
        $("#restrictStuffId").prop("disabled", true);
    }
},
    error: function(xhr, err) {
        alert("kala restriction doesn't work");
    }
    });

    return false;
    });

    // for submiting description kala data 
  $("#completDescription").submit(function(e) {
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            dataType: 'json',
            data: $(this).serialize(),
            success: function(data) {
                $('#completDescriptionbtn').css('display', 'none');
                $("#kalaRestictionbtn").css("display", "none");
                $("#stockSubmit").css("display", "none");
                $("#addToListSubmit").css("display","none");
                $("#submitChangePic").css("display","none");
                $("#submitSubGroup").css("display", "block");
            },
            error: function(xhr, err) {
                alert('description Kala is not submited');
            }
            
        });
        e.preventDefault();
    });

   
$("#calendarRadioBtn").on("change", ()=>{
    $("#timeTable").css("display", "table")
    $("#calendarStaff").css("display", "inline")
    $("#month").css("display", "inline")
    $("#year").css("display", "inline")
    $("#customerTable").css("display", "none")
    $("#customerStaff").css("display", "none")
})
$("#customerListRadioBtn").on("change", ()=>{
    $("#customerTable").css("display", "table")
    $("#customerStaff").css("display", "inline")
    $("#timeTable").css("display", "none")
    $("#calendarStaff").css("display", "none")
    $("#month").css("display", "none")
    $("#year").css("display", "none")
})


$("#rdSentN").on("change",function(){
    if($("#rdSentN").is(":checked")){
        $("#baseInfoN").prop("checked",true);
        $("#infoRdN").prop("checked",true);
        $("#deleteSentRdN").prop("checked",true);
        $("#editSentRdN").prop("checked",true);
        $("#seeSentRdN").prop("checked",true);
    }else{
        if(!$(".rdN").is(":checked")){
            $("#infoRdN").prop("checked",false);
            $("#infoRdN").trigger("change");
        }
        $("#deleteSentRdN").prop("checked",false);
        $("#editSentRdN").prop("checked",false);
        $("#seeSentRdN").prop("checked",false);
    }
});

$("#rdNotSentN").on("change",function(){
    if($("#rdNotSentN").is(":checked")){
        $("#baseInfoN").prop("checked",true);
        $("#infoRdN").prop("checked",true);
        $("#rdNotSentN").prop("checked",true);
        $("#deleteRdNotSentN").prop("checked",true);
        $("#editRdNotSentN").prop("checked",true);
        $("#seeRdNotSentN").prop("checked",true);
    }else{
        if(!$(".rdN").is(":checked")){
            $("#infoRdN").prop("checked",false);
            $("#infoRdN").trigger("change");
        }
        $("#rdNotSentN").prop("checked",false);
        $("#deleteRdNotSentN").prop("checked",false);
        $("#editRdNotSentN").prop("checked",false);
        $("#seeRdNotSentN").prop("checked",false);
    }
})


$("#addSaleLineN").on("change",function(){
    if($("#addSaleLineN").is(":checked")){
        $("#baseInfoN").prop("checked",true);
        $("#deleteSaleLineN").prop("checked",true);
        $("#editSaleLineN").prop("checked",true);
        $("#seeSaleLineN").prop("checked",true);
    }else{
        if(!$(".baseInfoN").is(":checked")){
            $("#baseInfoN").prop("checked",false);
            }
        $("#deleteSaleLineN").prop("checked",false);
        $("#editSaleLineN").prop("checked",false);
        $("#seeSaleLineN").prop("checked",false);
    }
})


$("#baseInfoSetting").on("change",function(){
    if($("#baseInfoSetting").is(":checked")){
        $("#baseInfoN").prop("checked",true);
        $("#InfoSettingAccess").prop("checked",true);
        $("#deleteSettingAccess").prop("checked",true);
        $("#editSettingAccess").prop("checked",true);
        $("#seeSettingAccess").prop("checked",true);

        $("#InfoSettingTarget").prop("checked",true);
        $("#deleteSettingTargetN").prop("checked",true);
        $("#editSettingTargetN").prop("checked",true);
        $("#seeSettingTargetN").prop("checked",true);

    }else{
        if(!$(".baseInfoN").is(":checked")){
            $("#baseInfoN").prop("checked",false);
            }
        $("#InfoSettingAccess").prop("checked",false);
        $("#deleteSettingAccess").prop("checked",false);
        $("#editSettingAccess").prop("checked",false);
        $("#seeSettingAccess").prop("checked",false);

        $("#InfoSettingTarget").prop("checked",false);
        $("#deleteSettingTargetN").prop("checked",false);
        $("#editSettingTargetN").prop("checked",false);
        $("#seeSettingTargetN").prop("checked",false);

    }
})

$("#baseInfoProfileN").on("change",function(){
    if($("#baseInfoProfileN").is(":checked")){
        $("#baseInfoN").prop("checked",true);
        $("#deleteProfileN").prop("checked",true);
        $("#editProfileN").prop("checked",true);
        $("#seeProfileN").prop("checked",true);
    }else{
        if(!$(".baseInfoN").is(":checked")){
            $("#baseInfoN").prop("checked",false);
            }
            $("#deleteProfileN").prop("checked",false);
            $("#editProfileN").prop("checked",false);
            $("#seeProfileN").prop("checked",false);
    }
})

$("#infoRdN").on("change",function(){
    if($("#infoRdN").is(":checked")){

        $("#baseInfoN").prop("checked",true);
        $("#rdSentN").prop("checked",true);
        $("#deleteSentRdN").prop("checked",true);
        $("#editSentRdN").prop("checked",true);
        $("#seeSentRdN").prop("checked",true);

        $("#rdNotSentN").prop("checked",true);
        $("#deleteRdNotSentN").prop("checked",true);
        $("#editRdNotSentN").prop("checked",true);
        $("#seeRdNotSentN").prop("checked",true);

    }else{
        if(!$(".baseInfoN").is(":checked")){
            
        $("#baseInfoN").prop("checked",false);
        }
        $("#rdSentN").prop("checked",false);
        $("#deleteSentRdN").prop("checked",false);
        $("#editSentRdN").prop("checked",false);
        $("#seeSentRdN").prop("checked",false);

        $("#rdNotSentN").prop("checked",false);
        $("#deleteRdNotSentN").prop("checked",false);
        $("#editRdNotSentN").prop("checked",false);
        $("#seeRdNotSentN").prop("checked",false);
    }
});

$("#seeProfileN").on("change",function(){
    if(!$("#seeProfileN").is(":checked")){
        $(".ProfileN").prop("checked",false);
        $("#baseInfoProfileN").prop("checked",false);
        $("#baseInfoProfileN").trigger("change");
    }else{
        $("#baseInfoProfileN").prop("checked",true);
        $("#baseInfoProfileN").trigger("change");
    }
})

$("#editProfileN").on("change",function(){
    if(!$("#editProfileN").is(":checked")){
        $("#deleteProfileN").prop("checked",false);
    }else{
        $("#seeProfileN").prop("checked",false);
        $("#baseInfoProfileN").prop("checked",true);
        $("#baseInfoProfileN").trigger("change");
    }
})

$("#deleteProfileN").on("change",function(){
    if(!$("#deleteProfileN").is(":checked")){
    }else{
        $(".ProfileN").prop("checked",true);
        $("#baseInfoProfileN").prop("checked",true);
        $("#baseInfoProfileN").trigger("change");
    }
})

//
$("#seeSentRdN").on("change",function(){
    if(!$("#seeSentRdN").is(":checked")){
        $("#rdSentN").prop("checked",false);
        $("#rdSentN").trigger("change");
    }else{
        $("#rdSentN").prop("checked",true);
        $("#rdSentN").trigger("change");
    }
})

$("#editSentRdN").on("change",function(){
    if(!$("#editSentRdN").is(":checked")){
        $("#deleteSentRdN").prop("checked",false);
    }else{
        $("#rdSentN").prop("checked",true);
        $("#rdSentN").trigger("change");
    }
});

$("#deleteSentRdN").on("change",function(){
    if(!$("#deleteSentRdN").is(":checked")){
    }else{
        $("#rdSentN").prop("checked",true);
        $("#rdSentN").trigger("change");
    }
})
//
$("#seeRdNotSentN").on("change",function(){
    if(!$("#seeRdNotSentN").is(":checked")){
        $("#rdNotSentN").prop("checked",false);
        $("#rdNotSentN").trigger("change");
    }else{
        $("#rdNotSentN").prop("checked",true);
        $("#rdNotSentN").trigger("change");
    }
})


$("#editRdNotSentN").on("change",function(){
    if(!$("#editRdNotSentN").is(":checked")){
        $("#deleteRdNotSentN").prop("checked",false);
    }else{
        $("#seeSentRdN").prop("checked",false);
        $("#rdNotSentN").prop("checked",true);
        $("#rdNotSentN").trigger("change");
    }
});


$("#deleteRdNotSentN").on("change",function(){
    if(!$("#deleteRdNotSentN").is(":checked")){
    }else{
        $("#rdNotSentN").prop("checked",true);
        $("#rdNotSentN").trigger("change");
    }
})

//
$("#seeSaleLineN").on("change",function(){
    if(!$("#seeSaleLineN").is(":checked")){
        $("#addSaleLineN").prop("checked",false);
        $("#addSaleLineN").trigger("change");
    }else{
        $("#addSaleLineN").prop("checked",true);
        $("#addSaleLineN").trigger("change");
    }
})


$("#editSaleLineN").on("change",function(){
    if(!$("#editSaleLineN").is(":checked")){
        $("#deleteSaleLineN").prop("checked",false);
    }else{
        $("#addSaleLineN").prop("checked",true);
        $("#addSaleLineN").trigger("change");
    }
});


$("#deleteSaleLineN").on("change",function(){
    if(!$("#deleteSaleLineN").is(":checked")){
    }else{
        $("#addSaleLineN").prop("checked",true);
        $("#addSaleLineN").trigger("change");
    }
})

//
$("#seeSettingAccess").on("change",function(){
    if(!$("#seeSettingAccess").is(":checked")){
        $("#InfoSettingAccess").prop("checked",false);
        $("#InfoSettingAccess").trigger("change");
    }else{
        $("#InfoSettingAccess").prop("checked",true);
        $("#InfoSettingAccess").trigger("change");
    }
})


$("#editSettingAccess").on("change",function(){
    if(!$("#editSettingAccess").is(":checked")){
        $("#deleteSettingAccess").prop("checked",false);
    }else{
        $("#InfoSettingAccess").prop("checked",true);
        $("#InfoSettingAccess").trigger("change");
    }
});


$("#deleteSettingAccess").on("change",function(){
    if(!$("#deleteSettingAccess").is(":checked")){
    }else{
        $("#InfoSettingAccess").prop("checked",true);
        $("#InfoSettingAccess").trigger("change");
    }
})
//
$("#seeSettingTargetN").on("change",function(){
    if(!$("#seeSettingTargetN").is(":checked")){
        $("#InfoSettingTarget").prop("checked",false);
        $("#InfoSettingTarget").trigger("change");
    }else{
        $("#InfoSettingTarget").prop("checked",true);
        $("#InfoSettingTarget").trigger("change");
    }
})


$("#editSettingTargetN").on("change",function(){
    if(!$("#editSettingTargetN").is(":checked")){
        $("#deleteSettingTargetN").prop("checked",false);
    }else{
        $("#InfoSettingTarget").prop("checked",true);
        $("#InfoSettingTarget").trigger("change");
    }
});


$("#deleteSettingTargetN").on("change",function(){
    if(!$("#deleteSettingTargetN").is(":checked")){
    }else{
        $("#InfoSettingTarget").prop("checked",true);
        $("#InfoSettingTarget").trigger("change");
    }
})
//
$("#seedeclareElementN").on("change",function(){
    if(!$("#seedeclareElementN").is(":checked")){
        $("#declareElementN").prop("checked",false);
        $("#declareElementN").trigger("change");
    }else{
        $("#declareElementN").prop("checked",true);
        $("#declareElementN").trigger("change");
    }
})


$("#editdeclareElementN").on("change",function(){
    if(!$("#editdeclareElementN").is(":checked")){
        $("#deletedeclareElementN").prop("checked",false);
    }else{
        $("#declareElementN").prop("checked",true);
        $("#declareElementN").trigger("change");
    }
});


$("#deletedeclareElementN").on("change",function(){
    if(!$("#deletedeclareElementN").is(":checked")){
    }else{
        $("#declareElementN").prop("checked",true);
        $("#declareElementN").trigger("change");
    }
});

$("#InfoSettingAccess").on("change",function(){
    if($("#InfoSettingAccess").is(":checked")){
        $("#baseInfoSetting").prop("checked",true);
        $("#baseInfoN").prop("checked",true);
        $("#InfoSettingAccess").prop("checked",true);
        $("#deleteSettingAccess").prop("checked",true);
        $("#editSettingAccess").prop("checked",true);
        $("#seeSettingAccess").prop("checked",true);
    }else{
        if(!$(".InfoSetting").is(":checked")){
            $("#baseInfoSetting").prop("checked",false);
            $("#baseInfoSetting").trigger("change");
        }
        $("#deleteSettingAccess").prop("checked",false);
        $("#editSettingAccess").prop("checked",false);
        $("#seeSettingAccess").prop("checked",false);
    }
});


$("#InfoSettingTarget").on("change",function(){
    if($("#InfoSettingTarget").is(":checked")){
        $("#baseInfoSetting").prop("checked",true);
        $("#baseInfoN").prop("checked",true);
        $("#deleteSettingTargetN").prop("checked",true);
        $("#editSettingTargetN").prop("checked",true);
        $("#seeSettingTargetN").prop("checked",true);
    }else{
        if(!$(".InfoSetting").is(":checked")){
            $("#baseInfoSetting").prop("checked",false);
            $("#baseInfoSetting").trigger("change");
        }
        $("#deleteSettingTargetN").prop("checked",false);
        $("#editSettingTargetN").prop("checked",false);
        $("#seeSettingTargetN").prop("checked",false);
    }
});



$("#baseInfoN").on("change",function(){
    if($("#baseInfoN").is(":checked")){
        $("#deleteProfileN").prop("checked",true);
        $("#editProfileN").prop("checked",true);
        $("#seeProfileN").prop("checked",true);
        $("#baseInfoProfileN").prop("checked",true);

        $("#rdSentN").prop("checked",true);
        $("#infoRdN").prop("checked",true);
        $("#deleteSentRdN").prop("checked",true);
        $("#editSentRdN").prop("checked",true);
        $("#seeSentRdN").prop("checked",true);

        $("#rdNotSentN").prop("checked",true);
        $("#deleteRdNotSentN").prop("checked",true);
        $("#editRdNotSentN").prop("checked",true);
        $("#seeRdNotSentN").prop("checked",true);

        $("#addSaleLineN").prop("checked",true);
        $("#deleteSaleLineN").prop("checked",true);
        $("#editSaleLineN").prop("checked",true);
        $("#seeSaleLineN").prop("checked",true);

        $("#baseInfoSetting").prop("checked",true);
        $("#InfoSettingAccess").prop("checked",true);
        $("#deleteSettingAccess").prop("checked",true);
        $("#editSettingAccess").prop("checked",true);
        $("#seeSettingAccess").prop("checked",true);

        $("#InfoSettingTarget").prop("checked",true);
        $("#deleteSettingTargetN").prop("checked",true);
        $("#editSettingTargetN").prop("checked",true);
        $("#seeSettingTargetN").prop("checked",true);
    }else{
        $("#rdSentN").prop("checked",false);
        $("#infoRdN").prop("checked",false);
        $("#deleteSentRdN").prop("checked",false);
        $("#editSentRdN").prop("checked",false);
        $("#seeSentRdN").prop("checked",false);

        $("#rdNotSentN").prop("checked",false);
        $("#deleteRdNotSentN").prop("checked",false);
        $("#editRdNotSentN").prop("checked",false);
        $("#seeRdNotSentN").prop("checked",false);

        $("#deleteProfileN").prop("checked",false);
        $("#editProfileN").prop("checked",false);
        $("#seeProfileN").prop("checked",false);
        $("#baseInfoProfileN").prop("checked",false);

        $("#addSaleLineN").prop("checked",false);
        $("#deleteSaleLineN").prop("checked",false);
        $("#editSaleLineN").prop("checked",false);
        $("#seeSaleLineN").prop("checked",false);

        $("#baseInfoSetting").prop("checked",false);
        $("#InfoSettingAccess").prop("checked",false);
        $("#deleteSettingAccess").prop("checked",false);
        $("#editSettingAccess").prop("checked",false);
        $("#seeSettingAccess").prop("checked",false);

        $("#InfoSettingTarget").prop("checked",false);
        $("#deleteSettingTargetN").prop("checked",false);
        $("#editSettingTargetN").prop("checked",false);
        $("#seeSettingTargetN").prop("checked",false);
    }
});



$("#oppTakhsisN").on("change",function(){
    if($("#oppTakhsisN").is(":checked")){
        $("#oppN").prop("checked",true);
        $("#oppManagerN").prop("checked",true);
        $("#oppHeadN").prop("checked",true);
        $("#oppBazaryabN").prop("checked",true);
        $("#seeManagerOppN").prop("checked",true);
        $("#seeBazaryabOppN").prop("checked",true);
        $("#seeHeadOppN").prop("checked",true);
        $("#seeBazaryabOppN").prop("checked",true);
    }else{
        if(!$(".oppPartN").is(":checked")){
            $("#oppN").prop("checked",false);
        }
        
        $("#oppManagerN").prop("checked",false);
        $("#oppHeadN").prop("checked",false);
        $("#oppBazaryabN").prop("checked",false);
        $("#seeManagerOppN").prop("checked",false);
        $("#seeBazaryabOppN").prop("checked",false);
        $("#seeHeadOppN").prop("checked",false);
    }
});

$("#oppDriverN").on("change",function(){
    if($("#oppDriverN").is(":checked")){
        $("#oppN").prop("checked",true);
        $("#oppDriverServiceN").prop("checked",true);
        $("#oppBargiriN").prop("checked",true);

        $("#seeoppDriverServiceN").prop("checked",true);
        $("#seeoppBargiriN").prop("checked",true);
    }else{
        if(!$(".oppPartN").is(":checked")){
            $("#oppN").prop("checked",false);
        }
        
        $("#oppDriverServiceN").prop("checked",false);
        $("#oppBargiriN").prop("checked",false);

        $("#seeoppDriverServiceN").prop("checked",false);
        $("#seeoppBargiriN").prop("checked",false);
    }
});


$("#oppNazarSanjiN").on("change",function(){
    if($("#oppNazarSanjiN").is(":checked")){
        $("#oppN").prop("checked",true);
        $("#todayoppNazarsanjiN").prop("checked",true);
        $("#pastoppNazarsanjiN").prop("checked",true);
        $("#DoneoppNazarsanjiN").prop("checked",true);

        $("#seetodayoppNazarsanjiN").prop("checked",true);
        $("#seepastoppNazarsanjiN").prop("checked",true);
        $("#seeDoneoppNazarsanjiN").prop("checked",true);
    }else{
        if(!$(".oppPartN").is(":checked")){
            $("#oppN").prop("checked",false);
        }
        
        $("#todayoppNazarsanjiN").prop("checked",false);
        $("#pastoppNazarsanjiN").prop("checked",false);
        $("#DoneoppNazarsanjiN").prop("checked",false);

        $("#seetodayoppNazarsanjiN").prop("checked",false);
        $("#seepastoppNazarsanjiN").prop("checked",false);
        $("#seeDoneoppNazarsanjiN").prop("checked",false);
    }
});


$("#OppupDownBonusN").on("change",function(){
    if($("#OppupDownBonusN").is(":checked")){
        $("#oppN").prop("checked",true);
        $("#AddOppupDownBonusN").prop("checked",true);
        $("#SubOppupDownBonusN").prop("checked",true);


        $("#seeAddOppupDownBonusN").prop("checked",true);
        $("#seeSubOppupDownBonusN").prop("checked",true);

    }else{
        if(!$(".oppPartN").is(":checked")){
            $("#oppN").prop("checked",false);
        }
        
        $("#AddOppupDownBonusN").prop("checked",false);
        $("#SubOppupDownBonusN").prop("checked",false);

        $("#seeAddOppupDownBonusN").prop("checked",false);
        $("#seeSubOppupDownBonusN").prop("checked",false);
    }
});

$("#oppRDN").on("change",function(){
    if($("#oppRDN").is(":checked")){
        $("#oppN").prop("checked",true);
        $("#AddedoppRDN").prop("checked",true);
        $("#NotAddedoppRDN").prop("checked",true);

        $("#seeAddedoppRDN").prop("checked",true);
        $("#seeNotAddedoppRDN").prop("checked",true);
    }else{
        if(!$(".oppPartN").is(":checked")){
            $("#oppN").prop("checked",false);
        }

        $("#AddedoppRDN").prop("checked",false);
        $("#NotAddedoppRDN").prop("checked",false);

        $("#seeAddedoppRDN").prop("checked",false);
        $("#seeNotAddedoppRDN").prop("checked",false);
    }
});

$("#oppCalendarN").on("change",function(){
    if($("#oppCalendarN").is(":checked")){
        $("#oppN").prop("checked",true);
        $("#oppjustCalendarN").prop("checked",true);
        $("#oppCustCalendarN").prop("checked",true);

        $("#seeoppjustCalendarN").prop("checked",true);
        $("#seeoppCustCalendarN").prop("checked",true);

    }else{
        if(!$(".oppPartN").is(":checked")){
            $("#oppN").prop("checked",false);
        }
        
        $("#oppjustCalendarN").prop("checked",false);
        $("#oppCustCalendarN").prop("checked",false);

        $("#seeoppjustCalendarN").prop("checked",false);
        $("#seeoppCustCalendarN").prop("checked",false);
    }
});


$("#alarmoppN").on("change",function(){
    if($("#alarmoppN").is(":checked")){
        $("#oppN").prop("checked",true);
        $("#allalarmoppN").prop("checked",true);
        $("#donealarmoppN").prop("checked",true);
        $("#NoalarmoppN").prop("checked",true);

        $("#seeallalarmoppN").prop("checked",true);
        $("#seedonealarmoppN").prop("checked",true);
        $("#seeNoalarmoppN").prop("checked",true);
    }else{
        if(!$(".oppPartN").is(":checked")){
            $("#oppN").prop("checked",false);
        }
        
        $("#allalarmoppN").prop("checked",false);
        $("#donealarmoppN").prop("checked",false);
        $("#NoalarmoppN").prop("checked",false);

        $("#seeallalarmoppN").prop("checked",false);
        $("#seedonealarmoppN").prop("checked",false);
        $("#seeNoalarmoppN").prop("checked",false);
    }
});

$("#massageOppN").on("change",function(){
    if($("#massageOppN").is(":checked")){
        $("#seemassageOppN").prop("checked",true);
        $("#oppN").prop("checked",true);
    }else{
        if(!$(".oppPartN").is(":checked")){
            $("#oppN").prop("checked",false);
        }
        $("#seemassageOppN").prop("checked",false);
    }
});

$("#justBargiriOppN").on("change",function(){
    if($("#justBargiriOppN").is(":checked")){
        $("#seejustBargiriOppN").prop("checked",true);
        $("#oppN").prop("checked",true);
    }else{
        if(!$(".oppPartN").is(":checked")){
            $("#oppN").prop("checked",false);
        }
        $("#seejustBargiriOppN").prop("checked",false);
    }
});


$("#oppManagerN").on("change",function(){
    if($("#oppManagerN").is(":checked")){
        $("#oppN").prop("checked",true);
        $("#oppTakhsisN").prop("checked",true);
        $("#seeManagerOppN").prop("checked",true);
    }else{
        if(!$(".oppTakhsisN").is(":checked")){
            $("#oppN").prop("checked",false);
            $("#oppTakhsisN").prop("checked",false);
        }
        $("#seeManagerOppN").prop("checked",false);
    }
});

$("#oppHeadN").on("change",function(){
    if($("#oppHeadN").is(":checked")){
        $("#oppN").prop("checked",true);
        $("#oppTakhsisN").prop("checked",true);
        $("#seeHeadOppN").prop("checked",true);
    }else{
        if(!$(".oppTakhsisN").is(":checked")){
            $("#oppN").prop("checked",false);
            $("#oppTakhsisN").prop("checked",false);
        }
        $("#seeHeadOppN").prop("checked",false);
    }
});

$("#oppBazaryabN").on("change",function(){
    if($("#oppBazaryabN").is(":checked")){
        $("#oppN").prop("checked",true);
        $("#oppTakhsisN").prop("checked",true);
        $("#seeBazaryabOppN").prop("checked",true);
    }else{
        if(!$(".oppTakhsisN").is(":checked")){
            $("#oppN").prop("checked",false);
            $("#oppTakhsisN").prop("checked",false);
        }
        $("#seeBazaryabOppN").prop("checked",false);
    }
});

$("#oppDriverServiceN").on("change",function(){
    if($("#oppDriverServiceN").is(":checked")){
        $("#oppN").prop("checked",true);
        $("#oppDriverN").prop("checked",true);
        $("#seeoppDriverServiceN").prop("checked",true);
    }else{
        if(!$(".oppTakhsisN").is(":checked")){
            $("#oppN").prop("checked",false);
            $("#oppDriverN").prop("checked",false);
        }
        $("#seeoppDriverServiceN").prop("checked",false);
    }
});

$("#oppBargiriN").on("change",function(){
    if($("#oppBargiriN").is(":checked")){
        $("#oppN").prop("checked",true);
        $("#oppDriverN").prop("checked",true);
        $("#seeoppBargiriN").prop("checked",true);
    }else{
        if(!$(".oppTakhsisN").is(":checked")){
            $("#oppN").prop("checked",false);
            $("#oppDriverN").prop("checked",false);
        }
        $("#seeoppBargiriN").prop("checked",false);
    }
});

$("#todayoppNazarsanjiN").on("change",function(){
    if($("#todayoppNazarsanjiN").is(":checked")){
        $("#oppN").prop("checked",true);
        $("#oppNazarSanjiN").prop("checked",true);
        $("#seetodayoppNazarsanjiN").prop("checked",true);
    }else{
        if(!$(".oppNazarSanjiN").is(":checked")){
            $("#oppN").prop("checked",false);
            $("#oppNazarSanjiN").prop("checked",false);
        }
        $("#seetodayoppNazarsanjiN").prop("checked",false);
    }
});

$("#pastoppNazarsanjiN").on("change",function(){
    if($("#pastoppNazarsanjiN").is(":checked")){
        $("#oppN").prop("checked",true);
        $("#oppNazarSanjiN").prop("checked",true);
        $("#seepastoppNazarsanjiN").prop("checked",true);
    }else{
        if(!$(".oppNazarSanjiN").is(":checked")){
            $("#oppN").prop("checked",false);
            $("#oppNazarSanjiN").prop("checked",false);
        }
        $("#seepastoppNazarsanjiN").prop("checked",false);
    }
});

$("#DoneoppNazarsanjiN").on("change",function(){
    if($("#DoneoppNazarsanjiN").is(":checked")){
        $("#oppN").prop("checked",true);
        $("#OppupDownBonusN").prop("checked",true);
        $("#seeDoneoppNazarsanjiN").prop("checked",true);
    }else{
        if(!$(".OppupDownBonusN").is(":checked")){
            $("#oppN").prop("checked",false);
            $("#OppupDownBonusN").prop("checked",false);
        }
        $("#seeDoneoppNazarsanjiN").prop("checked",false);
    }
});

$("#AddOppupDownBonusN").on("change",function(){
    if($("#AddOppupDownBonusN").is(":checked")){
        $("#oppN").prop("checked",true);
        $("#OppupDownBonusN").prop("checked",true);
        $("#seeAddOppupDownBonusN").prop("checked",true);
    }else{
        if(!$(".OppupDownBonusN").is(":checked")){
            $("#oppN").prop("checked",false);
            $("#OppupDownBonusN").prop("checked",false);
        }
        $("#seeAddOppupDownBonusN").prop("checked",false);
    }
});

$("#SubOppupDownBonusN").on("change",function(){
    if($("#SubOppupDownBonusN").is(":checked")){
        $("#oppN").prop("checked",true);
        $("#OppupDownBonusN").prop("checked",true);
        $("#seeSubOppupDownBonusN").prop("checked",true);
    }else{
        if(!$(".OppupDownBonusN").is(":checked")){
            $("#oppN").prop("checked",false);
            $("#OppupDownBonusN").prop("checked",false);
        }
        $("#seeSubOppupDownBonusN").prop("checked",false);
    }
});


$("#AddedoppRDN").on("change",function(){
    if($("#AddedoppRDN").is(":checked")){
        $("#oppN").prop("checked",true);
        $("#oppRDN").prop("checked",true);
        $("#seeAddedoppRDN").prop("checked",true);
    }else{
        if(!$(".oppRDN").is(":checked")){
            $("#oppN").prop("checked",false);
            $("#oppRDN").prop("checked",false);
        }
        $("#seeAddedoppRDN").prop("checked",false);
    }
});

$("#NotAddedoppRDN").on("change",function(){
    if($("#NotAddedoppRDN").is(":checked")){
        $("#oppN").prop("checked",true);
        $("#oppRDN").prop("checked",true);
        $("#seeNotAddedoppRDN").prop("checked",true);
    }else{
        if(!$(".oppRDN").is(":checked")){
            $("#oppN").prop("checked",false);
            $("#oppRDN").prop("checked",false);
        }
        $("#seeNotAddedoppRDN").prop("checked",false);
    }
});

$("#oppjustCalendarN").on("change",function(){
    if($("#oppjustCalendarN").is(":checked")){
        $("#oppN").prop("checked",true);
        $("#oppCalendarN").prop("checked",true);
        $("#seeoppjustCalendarN").prop("checked",true);
    }else{
        if(!$(".oppCalendarN").is(":checked")){
            $("#oppN").prop("checked",false);
            $("#oppCalendarN").prop("checked",false);
        }
        $("#seeoppjustCalendarN").prop("checked",false);
    }
});

$("#oppCustCalendarN").on("change",function(){
    if($("#oppCustCalendarN").is(":checked")){
        $("#oppN").prop("checked",true);
        $("#oppCalendarN").prop("checked",true);
        $("#seeoppCustCalendarN").prop("checked",true);
    }else{
        if(!$(".oppCalendarN").is(":checked")){
            $("#oppN").prop("checked",false);
            $("#oppCalendarN").prop("checked",false);
        }
        $("#seeoppCustCalendarN").prop("checked",false);
    }
});

$("#allalarmoppN").on("change",function(){
    if($("#allalarmoppN").is(":checked")){
        $("#oppN").prop("checked",true);
        $("#alarmoppN").prop("checked",true);
        $("#seeallalarmoppN").prop("checked",true);
    }else{
        if(!$(".alarmoppN").is(":checked")){
            $("#oppN").prop("checked",false);
            $("#alarmoppN").prop("checked",false);
        }
        $("#seeallalarmoppN").prop("checked",false);
    }
});

$("#donealarmoppN").on("change",function(){
    if($("#donealarmoppN").is(":checked")){
        $("#oppN").prop("checked",true);
        $("#alarmoppN").prop("checked",true);
        $("#seedonealarmoppN").prop("checked",true);
    }else{
        if(!$(".alarmoppN").is(":checked")){
            $("#oppN").prop("checked",false);
            $("#alarmoppN").prop("checked",false);
        }
        $("#seedonealarmoppN").prop("checked",false);
    }
});

$("#NoalarmoppN").on("change",function(){
    if($("#NoalarmoppN").is(":checked")){
        $("#oppN").prop("checked",true);
        $("#alarmoppN").prop("checked",true);
        $("#seeNoalarmoppN").prop("checked",true);
    }else{
        if(!$(".alarmoppN").is(":checked")){
            $("#oppN").prop("checked",false);
            $("#alarmoppN").prop("checked",false);
        }
        $("#seeNoalarmoppN").prop("checked",false);
    }
});

//
$("#seeManagerOppN").on("change",function(){
    if(!$("#seeManagerOppN").is(":checked")){
        $("#oppManagerN").prop("checked",false);
        $("#oppManagerN").trigger("change");
    }else{
        $("#oppManagerN").prop("checked",true);
        $("#oppManagerN").trigger("change");
    }
})


$("#editManagerOppN").on("change",function(){
    if(!$("#editManagerOppN").is(":checked")){
        $("#deleteManagerOppN").prop("checked",false);
    }else{
        $("#oppManagerN").prop("checked",true);

        $("#oppManagerN").trigger("change");
    }
});


$("#deleteManagerOppN").on("change",function(){
    if(!$("#deleteManagerOppN").is(":checked")){
    }else{
        $("#oppManagerN").prop("checked",true);
        $("#editManagerOppN").prop("checked",true);
        $("#oppManagerN").trigger("change");
    }
});

//
$("#seeHeadOppN").on("change",function(){
    if(!$("#seeHeadOppN").is(":checked")){
        $("#oppHeadN").prop("checked",false);
        $("#oppHeadN").trigger("change");
    }else{
        $("#oppHeadN").prop("checked",true);
        $("#oppHeadN").trigger("change");
    }
})


$("#editHeadOppN").on("change",function(){
    if(!$("#editHeadOppN").is(":checked")){
        $("#deleteHeadOppN").prop("checked",false);
    }else{
        $("#oppHeadN").prop("checked",true);

        $("#oppHeadN").trigger("change");
    }
});


$("#deleteHeadOppN").on("change",function(){
    if(!$("#deleteHeadOppN").is(":checked")){
    }else{
        $("#oppHeadN").prop("checked",true);
        $("#editHeadOppN").prop("checked",true);
        $("#oppHeadN").trigger("change");
    }
});
//
$("#seeBazaryabOppN").on("change",function(){
    if(!$("#seeBazaryabOppN").is(":checked")){
        $("#oppBazaryabN").prop("checked",false);
        $("#oppBazaryabN").trigger("change");
    }else{
        $("#oppBazaryabN").prop("checked",true);
        $("#oppBazaryabN").trigger("change");
    }
})


$("#editBazaryabOppN").on("change",function(){
    if(!$("#editBazaryabOppN").is(":checked")){
        $("#deleteBazaryabOppN").prop("checked",false);
    }else{
        $("#oppBazaryabN").prop("checked",true);

        $("#oppBazaryabN").trigger("change");
    }
});


$("#deleteBazaryabOppN").on("change",function(){
    if(!$("#deleteBazaryabOppN").is(":checked")){
    }else{
        $("#oppBazaryabN").prop("checked",true);
        $("#editBazaryabOppN").prop("checked",true);
        $("#oppBazaryabN").trigger("change");
    }
});

//
$("#seeoppDriverServiceN").on("change",function(){
    if(!$("#seeoppDriverServiceN").is(":checked")){
        $("#oppDriverServiceN").prop("checked",false);
        $("#oppDriverServiceN").trigger("change");
    }else{
        $("#oppDriverServiceN").prop("checked",true);
        $("#oppDriverServiceN").trigger("change");
    }
})


$("#editoppDriverServiceN").on("change",function(){
    if(!$("#editoppDriverServiceN").is(":checked")){
        $("#deleteoppDriverServiceN").prop("checked",false);
    }else{
        $("#oppDriverServiceN").prop("checked",true);

        $("#oppDriverServiceN").trigger("change");
    }
});


$("#deleteoppDriverServiceN").on("change",function(){
    if(!$("#deleteoppDriverServiceN").is(":checked")){
    }else{
        $("#oppDriverServiceN").prop("checked",true);
        $("#editoppDriverServiceN").prop("checked",true);
        $("#oppDriverServiceN").trigger("change");
    }
});
$("#oppN").on("change",function(){
    if($("#oppN").is(":checked")){
        $("#oppTakhsisN").prop("checked",true);
        $("#oppManagerN").prop("checked",true);
        $("#deleteManagerOppN").prop("checked",true);
        $("#editManagerOppN").prop("checked",true);
        $("#seeManagerOppN").prop("checked",true);


        $("#oppHeadN").prop("checked",true);
        $("#deleteHeadOppN").prop("checked",true);
        $("#editHeadOppN").prop("checked",true);
        $("#seeHeadOppN").prop("checked",true);


        $("#oppBazaryabN").prop("checked",true);
        $("#deleteBazaryabOppN").prop("checked",true);
        $("#editBazaryabOppN").prop("checked",true);
        $("#seeBazaryabOppN").prop("checked",true);


        $("#oppDriverN").prop("checked",true);
        $("#oppDriverServiceN").prop("checked",true);
        $("#deleteoppDriverServiceN").prop("checked",true);
        $("#editoppDriverServiceN").prop("checked",true);
        $("#seeoppDriverServiceN").prop("checked",true);


        $("#oppBargiriN").prop("checked",true);
        $("#deleteoppBargiriN").prop("checked",true);
        $("#editoppBargiriN").prop("checked",true);
        $("#seeoppBargiriN").prop("checked",true);

        $("#oppNazarSanjiN").prop("checked",true);
        $("#todayoppNazarsanjiN").prop("checked",true);
        $("#deletetodayoppNazarsanjiN").prop("checked",true);
        $("#edittodayoppNazarsanjiN").prop("checked",true);
        $("#seetodayoppNazarsanjiN").prop("checked",true);


        $("#pastoppNazarsanjiN").prop("checked",true);
        $("#deletepastoppNazarsanjiN").prop("checked",true);
        $("#editpastoppNazarsanjiN").prop("checked",true);
        $("#seepastoppNazarsanjiN").prop("checked",true);


        $("#DoneoppNazarsanjiN").prop("checked",true);
        $("#deleteDoneoppNazarsanjiN").prop("checked",true);
        $("#editDoneoppNazarsanjiN").prop("checked",true);
        $("#seeDoneoppNazarsanjiN").prop("checked",true);


        $("#OppupDownBonusN").prop("checked",true);
        $("#AddOppupDownBonusN").prop("checked",true);
        $("#deleteAddOppupDownBonusN").prop("checked",true);
        $("#editAddOppupDownBonusN").prop("checked",true);
        $("#seeAddOppupDownBonusN").prop("checked",true);


        $("#SubOppupDownBonusN").prop("checked",true);
        $("#deleteSubOppupDownBonusN").prop("checked",true);
        $("#editSubOppupDownBonusN").prop("checked",true);
        $("#seeSubOppupDownBonusN").prop("checked",true);


        $("#oppRDN").prop("checked",true);
        $("#AddedoppRDN").prop("checked",true);
        $("#deleteAddedoppRDN").prop("checked",true);
        $("#editAddedoppRDN").prop("checked",true);
        $("#seeAddedoppRDN").prop("checked",true);


        $("#NotAddedoppRDN").prop("checked",true);
        $("#deleteNotAddedoppRDN").prop("checked",true);
        $("#editNotAddedoppRDN").prop("checked",true);
        $("#seeNotAddedoppRDN").prop("checked",true);


        $("#oppCalendarN").prop("checked",true);
        $("#oppjustCalendarN").prop("checked",true);
        $("#deleteoppjustCalendarN").prop("checked",true);
        $("#editoppjustCalendarN").prop("checked",true);
        $("#seeoppjustCalendarN").prop("checked",true);


        $("#oppCustCalendarN").prop("checked",true);
        $("#deleteoppCustCalendarN").prop("checked",true);
        $("#editoppCustCalendarN").prop("checked",true);
        $("#seeoppCustCalendarN").prop("checked",true);


        $("#alarmoppN").prop("checked",true);
        $("#allalarmoppN").prop("checked",true);
        $("#deleteallalarmoppN").prop("checked",true);
        $("#editallalarmoppN").prop("checked",true);
        $("#seeallalarmoppN").prop("checked",true);


        $("#donealarmoppN").prop("checked",true);
        $("#deletedonealarmoppN").prop("checked",true);
        $("#editdonealarmoppN").prop("checked",true);
        $("#seedonealarmoppN").prop("checked",true);


        $("#NoalarmoppN").prop("checked",true);
        $("#deleteNoalarmoppN").prop("checked",true);
        $("#editNoalarmoppN").prop("checked",true);
        $("#seeNoalarmoppN").prop("checked",true);


        $("#massageOppN").prop("checked",true);
        $("#deletemassageOppN").prop("checked",true);
        $("#editmassageOppN").prop("checked",true);
        $("#seemassageOppN").prop("checked",true);


        $("#justBargiriOppN").prop("checked",true);
        $("#deletejustBargiriOppN").prop("checked",true);
        $("#editjustBargiriOppN").prop("checked",true);
        $("#seejustBargiriOppN").prop("checked",true);
    }else{
        $("#oppTakhsisN").prop("checked",false);
        $("#oppManagerN").prop("checked",false);
        $("#deleteManagerOppN").prop("checked",false);
        $("#editManagerOppN").prop("checked",false);
        $("#seeManagerOppN").prop("checked",false);
        
        
        $("#oppHeadN").prop("checked",false);
        $("#deleteHeadOppN").prop("checked",false);
        $("#editHeadOppN").prop("checked",false);
        $("#seeHeadOppN").prop("checked",false);
        
        
        $("#oppBazaryabN").prop("checked",false);
        $("#deleteBazaryabOppN").prop("checked",false);
        $("#editBazaryabOppN").prop("checked",false);
        $("#seeBazaryabOppN").prop("checked",false);
        
        
        $("#oppDriverN").prop("checked",false);
        $("#oppDriverServiceN").prop("checked",false);
        $("#deleteoppDriverServiceN").prop("checked",false);
        $("#editoppDriverServiceN").prop("checked",false);
        $("#seeoppDriverServiceN").prop("checked",false);
        
        
        $("#oppBargiriN").prop("checked",false);
        $("#deleteoppBargiriN").prop("checked",false);
        $("#editoppBargiriN").prop("checked",false);
        $("#seeoppBargiriN").prop("checked",false);
        
        $("#oppNazarSanjiN").prop("checked",false);
        $("#todayoppNazarsanjiN").prop("checked",false);
        $("#deletetodayoppNazarsanjiN").prop("checked",false);
        $("#edittodayoppNazarsanjiN").prop("checked",false);
        $("#seetodayoppNazarsanjiN").prop("checked",false);
        
        
        $("#pastoppNazarsanjiN").prop("checked",false);
        $("#deletepastoppNazarsanjiN").prop("checked",false);
        $("#editpastoppNazarsanjiN").prop("checked",false);
        $("#seepastoppNazarsanjiN").prop("checked",false);
        
        
        $("#DoneoppNazarsanjiN").prop("checked",false);
        $("#deleteDoneoppNazarsanjiN").prop("checked",false);
        $("#editDoneoppNazarsanjiN").prop("checked",false);
        $("#seeDoneoppNazarsanjiN").prop("checked",false);
        
        
        $("#OppupDownBonusN").prop("checked",false);
        $("#AddOppupDownBonusN").prop("checked",false);
        $("#deleteAddOppupDownBonusN").prop("checked",false);
        $("#editAddOppupDownBonusN").prop("checked",false);
        $("#seeAddOppupDownBonusN").prop("checked",false);
        
        
        $("#SubOppupDownBonusN").prop("checked",false);
        $("#deleteSubOppupDownBonusN").prop("checked",false);
        $("#editSubOppupDownBonusN").prop("checked",false);
        $("#seeSubOppupDownBonusN").prop("checked",false);
        
        
        $("#oppRDN").prop("checked",false);
        $("#AddedoppRDN").prop("checked",false);
        $("#deleteAddedoppRDN").prop("checked",false);
        $("#editAddedoppRDN").prop("checked",false);
        $("#seeAddedoppRDN").prop("checked",false);
        
        
        $("#NotAddedoppRDN").prop("checked",false);
        $("#deleteNotAddedoppRDN").prop("checked",false);
        $("#editNotAddedoppRDN").prop("checked",false);
        $("#seeNotAddedoppRDN").prop("checked",false);
        
        
        $("#oppCalendarN").prop("checked",false);
        $("#oppjustCalendarN").prop("checked",false);
        $("#deleteoppjustCalendarN").prop("checked",false);
        $("#editoppjustCalendarN").prop("checked",false);
        $("#seeoppjustCalendarN").prop("checked",false);
        
        
        $("#oppCustCalendarN").prop("checked",false);
        $("#deleteoppCustCalendarN").prop("checked",false);
        $("#editoppCustCalendarN").prop("checked",false);
        $("#seeoppCustCalendarN").prop("checked",false);
        
        
        $("#alarmoppN").prop("checked",false);
        $("#allalarmoppN").prop("checked",false);
        $("#deleteallalarmoppN").prop("checked",false);
        $("#editallalarmoppN").prop("checked",false);
        $("#seeallalarmoppN").prop("checked",false);
        
        
        $("#donealarmoppN").prop("checked",false);
        $("#deletedonealarmoppN").prop("checked",false);
        $("#editdonealarmoppN").prop("checked",false);
        $("#seedonealarmoppN").prop("checked",false);
        
        
        $("#NoalarmoppN").prop("checked",false);
        $("#deleteNoalarmoppN").prop("checked",false);
        $("#editNoalarmoppN").prop("checked",false);
        $("#seeNoalarmoppN").prop("checked",false);
        
        
        $("#massageOppN").prop("checked",false);
        $("#deletemassageOppN").prop("checked",false);
        $("#editmassageOppN").prop("checked",false);
        $("#seemassageOppN").prop("checked",false);
        
        
        $("#justBargiriOppN").prop("checked",false);
        $("#deletejustBargiriOppN").prop("checked",false);
        $("#editjustBargiriOppN").prop("checked",false);
        $("#seejustBargiriOppN").prop("checked",false); 
    }
});


$("#amalKardreportN").on("change",function(){
    if($("#amalKardreportN").is(":checked")){
        $("#reportN").prop("checked",true);
        $("#managerreportN").prop("checked",true);
        $("#HeadreportN").prop("checked",true);
        $("#poshtibanreportN").prop("checked",true);
        $("#bazaryabreportN").prop("checked",true);
        $("#reportDriverN").prop("checked",true);
        
        $("#seemanagerreportN").prop("checked",true);
        $("#seeHeadreportN").prop("checked",true);
        $("#seebazaryabreportN").prop("checked",true);
        $("#seeposhtibanreportN").prop("checked",true);
        $("#seereportDriverN").prop("checked",true);
    }else{
        if(!$(".reportPartN").is(":checked")){
            $("#reportN").prop("checked",false);
        }
        $("#managerreportN").prop("checked",false);
        $("#HeadreportN").prop("checked",false);
        $("#poshtibanreportN").prop("checked",false);
        $("#bazaryabreportN").prop("checked",false);
        $("#reportDriverN").prop("checked",false);

        $("#seemanagerreportN").prop("checked",false);
        $("#seeHeadreportN").prop("checked",false);
        $("#seebazaryabreportN").prop("checked",false);
        $("#seeposhtibanreportN").prop("checked",false);
        $("#seereportDriverN").prop("checked",false);
    }
});

$("#trazEmployeeReportN").on("change",function(){
    if($("#trazEmployeeReportN").is(":checked")){
        $("#reportN").prop("checked",true);
        $("#seetrazEmployeeReportN").prop("checked",true);
    }else{
        if(!$(".reportPartN").is(":checked")){
            $("#reportN").prop("checked",false);
        }
        $("#seetrazEmployeeReportN").prop("checked",false);
    }
});

$("#customerReportN").on("change",function(){
    if($("#customerReportN").is(":checked")){
        $("#reportN").prop("checked",true);
        $("#seecustomerReportN").prop("checked",true);
    }else{
        if(!$(".reportPartN").is(":checked")){
            $("#reportN").prop("checked",false);
        }
        $("#seecustomerReportN").prop("checked",false);
    }
});
$("#goodsReport").on("change",function(){
    if($("#goodsReport").is(":checked")){
        $("#reportN").prop("checked",true);
        $("#salegoodsReportN").prop("checked",true);
        $("#returnedgoodsReportN").prop("checked",true);
        $("#NoExistgoodsReportN").prop("checked",true);
        $("#nosalegoodsReportN").prop("checked",true);

        $("#seesalegoodsReportN").prop("checked",true);
        $("#seereturnedgoodsReportN").prop("checked",true);
        $("#seeNoExistgoodsReportN").prop("checked",true);
        $("#seenosalegoodsReportN").prop("checked",true);
    }else{
        if(!$(".reportPartN").is(":checked")){
            $("#reportN").prop("checked",false);
        }
        $("#salegoodsReportN").prop("checked",false);
        $("#returnedgoodsReportN").prop("checked",false);
        $("#NoExistgoodsReportN").prop("checked",false);
        $("#nosalegoodsReportN").prop("checked",false);

        $("#seesalegoodsReportN").prop("checked",false);
        $("#seereturnedgoodsReportN").prop("checked",false);
        $("#seeNoExistgoodsReportN").prop("checked",false);
        $("#seenosalegoodsReportN").prop("checked",false);
    }
});

$("#returnedReportgoodsReportN").on("change",function(){
    if($("#returnedReportgoodsReportN").is(":checked")){
        $("#reportN").prop("checked",true);
        $("#returnedNTasReportgoodsReportN").prop("checked",true);
        $("#tasgoodsReprtN").prop("checked",true);

        $("#seereturnedNTasReportgoodsReportN").prop("checked",true);
        $("#seetasgoodsReprtN").prop("checked",true);
    }else{
        if(!$(".reportPartN").is(":checked")){
            $("#reportN").prop("checked",false);
        }
        $("#returnedNTasReportgoodsReportN").prop("checked",false);
        $("#tasgoodsReprtN").prop("checked",false);

        $("#seereturnedNTasReportgoodsReportN").prop("checked",false);
        $("#seetasgoodsReprtN").prop("checked",false);
    }
});


$("#goodsbargiriReportN").on("change",function(){
    if($("#goodsbargiriReportN").is(":checked")){
        $("#reportN").prop("checked",true);
        $("#seegoodsbargiriReportN").prop("checked",true);
    }else{
        if(!$(".reportPartN").is(":checked")){
            $("#reportN").prop("checked",false);
        }
        $("#seegoodsbargiriReportN").prop("checked",false);
    }
});

$("#managerreportN").on("change",function(){
    if($("#managerreportN").is(":checked")){
        $("#reportN").prop("checked",true);
        $("#amalKardreportN").prop("checked",true);
        $("#seemanagerreportN").prop("checked",true);
    }else{
        if(!$(".amalKardreportN").is(":checked")){
            $("#reportN").prop("checked",false);
            $("#amalKardreportN").prop("checked",false);
        }
        $("#seemanagerreportN").prop("checked",false);
    }
});

$("#HeadreportN").on("change",function(){
    if($("#HeadreportN").is(":checked")){
        $("#reportN").prop("checked",true);
        $("#amalKardreportN").prop("checked",true);
        $("#seeHeadreportN").prop("checked",true);
    }else{
        if(!$(".amalKardreportN").is(":checked")){
            $("#reportN").prop("checked",false);
            $("#amalKardreportN").prop("checked",false);
        }
        $("#seeHeadreportN").prop("checked",false);
    }
});

$("#poshtibanreportN").on("change",function(){
    if($("#poshtibanreportN").is(":checked")){
        $("#reportN").prop("checked",true);
        $("#amalKardreportN").prop("checked",true);
        $("#seeposhtibanreportN").prop("checked",true);
    }else{
        if(!$(".amalKardreportN").is(":checked")){
            $("#reportN").prop("checked",false);
            $("#amalKardreportN").prop("checked",false);
        }
        $("#seeposhtibanreportN").prop("checked",false);
    }
});


$("#bazaryabreportN").on("change",function(){
    if($("#bazaryabreportN").is(":checked")){
        $("#reportN").prop("checked",true);
        $("#amalKardreportN").prop("checked",true);
        $("#seebazaryabreportN").prop("checked",true);
    }else{
        if(!$(".amalKardreportN").is(":checked")){
            $("#reportN").prop("checked",false);
            $("#amalKardreportN").prop("checked",false);
        }
        $("#seebazaryabreportN").prop("checked",false);
    }
});

$("#reportDriverN").on("change",function(){
    if($("#reportDriverN").is(":checked")){
        $("#reportN").prop("checked",true);
        $("#amalKardreportN").prop("checked",true);
        $("#seereportDriverN").prop("checked",true);
    }else{
        if(!$(".amalKardreportN").is(":checked")){
            $("#reportN").prop("checked",false);
            $("#amalKardreportN").prop("checked",false);
        }
        $("#seereportDriverN").prop("checked",false);
    }
});

 
$("#salegoodsReportN ").on("change",function(){
    if($("#salegoodsReportN ").is(":checked")){
        $("#reportN").prop("checked",true);
        $("#goodsReport").prop("checked",true);
        $("#seesalegoodsReportN ").prop("checked",true);
    }else{
        if(!$(".goodsReport").is(":checked")){
            $("#reportN").prop("checked",false);
            $("#goodsReport").prop("checked",false);
        }
        $("#seesalegoodsReportN ").prop("checked",false);
    }
});


$("#returnedgoodsReportN ").on("change",function(){
    if($("#returnedgoodsReportN ").is(":checked")){
        $("#reportN").prop("checked",true);
        $("#goodsReport").prop("checked",true);
        $("#seereturnedgoodsReportN ").prop("checked",true);
    }else{
        if(!$(".goodsReport").is(":checked")){
            $("#reportN").prop("checked",false);
            $("#goodsReport").prop("checked",false);
        }
        $("#seereturnedgoodsReportN ").prop("checked",false);
    }
});
$("#NoExistgoodsReportN ").on("change",function(){
    if($("#NoExistgoodsReportN ").is(":checked")){
        $("#reportN").prop("checked",true);
        $("#goodsReport").prop("checked",true);
        $("#seeNoExistgoodsReportN ").prop("checked",true);
    }else{
        if(!$(".goodsReport").is(":checked")){
            $("#reportN").prop("checked",false);
            $("#goodsReport").prop("checked",false);
        }
        $("#seeNoExistgoodsReportN ").prop("checked",false);
    }
});
$("#nosalegoodsReportN ").on("change",function(){
    if($("#nosalegoodsReportN ").is(":checked")){
        $("#reportN").prop("checked",true);
        $("#goodsReport").prop("checked",true);
        $("#seenosalegoodsReportN ").prop("checked",true);
    }else{
        if(!$(".goodsReport").is(":checked")){
            $("#reportN").prop("checked",false);
            $("#goodsReport").prop("checked",false);
        }
        $("#seenosalegoodsReportN ").prop("checked",false);
    }
});

$("#returnedNTasReportgoodsReportN ").on("change",function(){
    if($("#returnedNTasReportgoodsReportN ").is(":checked")){
        $("#reportN").prop("checked",true);
        $("#returnedReportgoodsReportN").prop("checked",true);
        $("#seereturnedNTasReportgoodsReportN ").prop("checked",true);
    }else{
        if(!$(".returnedReportgoodsReportN").is(":checked")){
            $("#reportN").prop("checked",false);
            $("#returnedReportgoodsReportN").prop("checked",false);
        }
        $("#seereturnedNTasReportgoodsReportN ").prop("checked",false);
    }
});

$("#tasgoodsReprtN ").on("change",function(){
    if($("#tasgoodsReprtN ").is(":checked")){
        $("#reportN").prop("checked",true);
        $("#returnedReportgoodsReportN").prop("checked",true);
        $("#seetasgoodsReprtN ").prop("checked",true);
    }else{
        if(!$(".returnedReportgoodsReportN").is(":checked")){
            $("#reportN").prop("checked",false);
            $("#returnedReportgoodsReportN").prop("checked",false);
        }
        $("#seetasgoodsReprtN ").prop("checked",false);
    }
});

$(".reportN").on("change",function(){
    if($(".reportN").is(":checked")){

        $("#amalKardreportN").prop("checked",true);
        $("#managerreportN").prop("checked",true);
        $("#deletemanagerreportN").prop("checked",true);
        $("#editmanagerreportN").prop("checked",true);
        $("#seemanagerreportN").prop("checked",true);


        $("#HeadreportN").prop("checked",true);
        $("#deleteHeadreportN").prop("checked",true);
        $("#editHeadreportN").prop("checked",true);
        $("#seeHeadreportN").prop("checked",true);


        $("#poshtibanreportN").prop("checked",true);
        $("#deleteposhtibanreportN").prop("checked",true);
        $("#editposhtibanreportN").prop("checked",true);
        $("#seeposhtibanreportN").prop("checked",true);


        $("#bazaryabreportN").prop("checked",true);
        $("#deletebazaryabreportN").prop("checked",true);
        $("#editbazaryabreportN").prop("checked",true);
        $("#seebazaryabreportN").prop("checked",true);


        $("#reportDriverN").prop("checked",true);
        $("#deletereportDriverN").prop("checked",true);
        $("#editreportDriverN").prop("checked",true);
        $("#seereportDriverN").prop("checked",true);


        $("#trazEmployeeReportN").prop("checked",true);
        $("#deletetrazEmployeeReportN").prop("checked",true);
        $("#edittrazEmployeeReportN").prop("checked",true);
        $("#seetrazEmployeeReportN").prop("checked",true);


        $("#customerReportN").prop("checked",true);
        $("#deletecustomerReportN").prop("checked",true);
        $("#editcustomerReportN").prop("checked",true);
        $("#seecustomerReportN").prop("checked",true);


        $("#goodsReport").prop("checked",true);
        $("#salegoodsReportN").prop("checked",true);
        $("#deletesalegoodsReportN").prop("checked",true);
        $("#editsalegoodsReportN").prop("checked",true);
        $("#seesalegoodsReportN").prop("checked",true);


        $("#returnedgoodsReportN").prop("checked",true);
        $("#deletereturnedgoodsReportN").prop("checked",true);
        $("#editturnedgoodsReportN").prop("checked",true);
        $("#seereturnedgoodsReportN").prop("checked",true);


        $("#NoExistgoodsReportN").prop("checked",true);
        $("#deleteNoExistgoodsReportN").prop("checked",true);
        $("#editNoExistgoodsReportN").prop("checked",true);
        $("#seeNoExistgoodsReportN").prop("checked",true);


        $("#nosalegoodsReportN").prop("checked",true);
        $("#deletenosalegoodsReportN").prop("checked",true);
        $("#editnosalegoodsReportN").prop("checked",true);
        $("#seenosalegoodsReportN").prop("checked",true);


        $("#returnedReportgoodsReportN").prop("checked",true);
        $("#returnedNTasReportgoodsReportN").prop("checked",true);
        $("#deletereturnedNTasReportgoodsReportN").prop("checked",true);
        $("#editreturnedgoodsReportN").prop("checked",true);
        $("#seereturnedNTasReportgoodsReportN").prop("checked",true);


        $("#tasgoodsReprtN").prop("checked",true);
        $("#deletetasgoodsReprtN").prop("checked",true);
        $("#edittasgoodsReprtN").prop("checked",true);
        $("#seetasgoodsReprtN").prop("checked",true);


        $("#goodsbargiriReportN").prop("checked",true);
        $("#deletegoodsbargiriReportN").prop("checked",true);
        $("#editgoodsbargiriReportN").prop("checked",true);
        $("#seegoodsbargiriReportN").prop("checked",true);

    }else{
        $("#amalKardreportN").prop("checked",false);
        $("#managerreportN").prop("checked",false);
        $("#deletemanagerreportN").prop("checked",false);
        $("#editmanagerreportN").prop("checked",false);
        $("#seemanagerreportN").prop("checked",false);


        $("#HeadreportN").prop("checked",false);
        $("#deleteHeadreportN").prop("checked",false);
        $("#editHeadreportN").prop("checked",false);
        $("#seeHeadreportN").prop("checked",false);


        $("#poshtibanreportN").prop("checked",false);
        $("#deleteposhtibanreportN").prop("checked",false);
        $("#editposhtibanreportN").prop("checked",false);
        $("#seeposhtibanreportN").prop("checked",false);


        $("#bazaryabreportN").prop("checked",false);
        $("#deletebazaryabreportN").prop("checked",false);
        $("#editbazaryabreportN").prop("checked",false);
        $("#seebazaryabreportN").prop("checked",false);


        $("#reportDriverN").prop("checked",false);
        $("#deletereportDriverN").prop("checked",false);
        $("#editreportDriverN").prop("checked",false);
        $("#seereportDriverN").prop("checked",false);


        $("#trazEmployeeReportN").prop("checked",false);
        $("#deletetrazEmployeeReportN").prop("checked",false);
        $("#edittrazEmployeeReportN").prop("checked",false);
        $("#seetrazEmployeeReportN").prop("checked",false);


        $("#customerReportN").prop("checked",false);
        $("#deletecustomerReportN").prop("checked",false);
        $("#editcustomerReportN").prop("checked",false);
        $("#seecustomerReportN").prop("checked",false);


        $("#goodsReport").prop("checked",false);
        $("#salegoodsReportN").prop("checked",false);
        $("#deletesalegoodsReportN").prop("checked",false);
        $("#editsalegoodsReportN").prop("checked",false);
        $("#seesalegoodsReportN").prop("checked",false);


        $("#returnedgoodsReportN").prop("checked",false);
        $("#deletereturnedgoodsReportN").prop("checked",false);
        $("#editturnedgoodsReportN").prop("checked",false);
        $("#seereturnedgoodsReportN").prop("checked",false);


        $("#NoExistgoodsReportN").prop("checked",false);
        $("#deleteNoExistgoodsReportN").prop("checked",false);
        $("#editNoExistgoodsReportN").prop("checked",false);
        $("#seeNoExistgoodsReportN").prop("checked",false);


        $("#nosalegoodsReportN").prop("checked",false);
        $("#deletenosalegoodsReportN").prop("checked",false);
        $("#editnosalegoodsReportN").prop("checked",false);
        $("#seenosalegoodsReportN").prop("checked",false);


        $("#returnedReportgoodsReportN").prop("checked",false);
        $("#returnedNTasReportgoodsReportN").prop("checked",false);
        $("#deletereturnedNTasReportgoodsReportN").prop("checked",false);
        $("#editreturnedgoodsReportN").prop("checked",false);
        $("#seereturnedNTasReportgoodsReportN").prop("checked",false);


        $("#tasgoodsReprtN").prop("checked",false);
        $("#deletetasgoodsReprtN").prop("checked",false);
        $("#edittasgoodsReprtN").prop("checked",false);
        $("#seetasgoodsReprtN").prop("checked",false);


        $("#goodsbargiriReportN").prop("checked",false);
        $("#deletegoodsbargiriReportN").prop("checked",false);
        $("#editgoodsbargiriReportN").prop("checked",false);
        $("#seegoodsbargiriReportN").prop("checked",false);
    }
})

// Create root and chart
var root = am5.Root.new("chartdiv");
root.setThemes([am5themes_Animated.new(root)]);

var chart = root.container.children.push(
    am5xy.XYChart.new(root, {
        wheelY: "zoomX",
    })
);

// Define data
var data = [
    {
        date: new Date(2021, 0, 1).getTime(),
        value: 100,
    },
    {
        date: new Date(2021, 0, 2).getTime(),
        value: 320,
    },
    {
        date: new Date(2021, 0, 3).getTime(),
        value: 216,
    },
    {
        date: new Date(2021, 0, 4).getTime(),
        value: 150,
    },
    {
        date: new Date(2021, 0, 5).getTime(),
        value: 156,
    },
    {
        date: new Date(2021, 0, 6).getTime(),
        value: 199,
    },
    {
        date: new Date(2021, 0, 7).getTime(),
        value: 114,
    },
    {
        date: new Date(2021, 0, 8).getTime(),
        value: 269,
    },
    {
        date: new Date(2021, 0, 9).getTime(),
        value: 190,
    },
    {
        date: new Date(2021, 0, 10).getTime(),
        value: 380,
    },
    {
        date: new Date(2021, 0, 11).getTime(),
        value: 250,
    },
    {
        date: new Date(2021, 0, 12).getTime(),
        value: 110,
    },
    {
        date: new Date(2021, 0, 13).getTime(),
        value: 185,
    },
    {
        date: new Date(2021, 0, 14).getTime(),
        value: 105,
    },
];

// Create Y-axis
var yAxis = chart.yAxes.push(
    am5xy.ValueAxis.new(root, {
        extraTooltipPrecision: 1,
        renderer: am5xy.AxisRendererY.new(root, {
            minGridDistance: 30,
        }),
    })
);

// Create X-Axis
let xAxis = chart.xAxes.push(
    am5xy.DateAxis.new(root, {
        baseInterval: { timeUnit: "day", count: 1 },
        renderer: am5xy.AxisRendererX.new(root, {
            minGridDistance: 20,
            cellStartLocation: 0.2,
            cellEndLocation: 0.8,
        }),
    })
);

// Create series
function createSeries(name, field) {
    var series = chart.series.push(
        am5xy.ColumnSeries.new(root, {
            name: name,
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: field,
            valueXField: "date",
            tooltip: am5.Tooltip.new(root, {}),
            clustered: true,
        })
    );

    series
        .get("tooltip")
        .label.set("text", "[bold]{name}[/]\n{valueX.formatDate()}: {valueY}");
    series.data.setAll(data);

    return series;
}

var series1 = createSeries("Series #1", "value");

// Create axis ranges
function createRange(series, value, endValue, color) {
    var range = series.createAxisRange(
        yAxis.makeDataItem({
            value: value,
            endValue: endValue,
        })
    );

    range.columns.template.setAll({
        fill: color,
        stroke: color,
    });

    range.axisDataItem.get("axisFill").setAll({
        fill: color,
        fillOpacity: 0.05,
        visible: true,
    });
}

createRange(series1, 125, 275, am5.color(0xff621f));

// Add cursor
chart.set(
    "cursor",
    am5xy.XYCursor.new(root, {
        behavior: "zoomX",
        xAxis: xAxis,
    })
);

xAxis.set(
    "tooltip",
    am5.Tooltip.new(root, {
        themeTags: ["axis"],
    })
);

yAxis.set(
    "tooltip",
    am5.Tooltip.new(root, {
        themeTags: ["axis"],
    })
);
