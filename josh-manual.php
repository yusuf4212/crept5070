<?php
    function manualinput() { ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

        <div class="container text-center">
            <div class="row">
                <div class="col">
                    <h2>Manual Input Donasi</h2>
                </div>
            </div>
        </div>
        <div class="container input">
            <div class="row">
                <div class="col-3">
                    <h4>form input</h4>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="campaign-id" class="form-label">Campaign Id:</label>
                    <input type="text" class="form-control" id="campaign-id" placeholder="campaign id" aria-label="campaign id">
                </div>
                <div class="col">
                    <label for="invoice-id" class="form-label">Invoice :</label>
                    <input type="text" class="form-control" id="invoice-id" placeholder="invoice id" aria-label="invoice id">
                </div>
                <div class="col">
                    <label for="repeatnew" class="form-label">Baru / Ulang :</label>
                    <input type="text" class="form-control" id="repeatnew" placeholder="Baru/Ulang" aria-label="repeatnew">
                </div>

            </div>
            <div class="row">
                <div class="col">
                    <label for="user-id" class="form-label">User Id:</label>
                    <input type="number" class="form-control" id="user-id" placeholder="user id" aria-label="user id">
                </div>
                <div class="col">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" class="form-control" id="name" placeholder="name" aria-label="name">
                </div>
                <div class="col">
                    <label for="whatsapp" class="form-label">Whatsapp:</label>
                    <input type="number" class="form-control" id="whatsapp" placeholder="whatsapp" aria-label="whatsapp">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="email" class="form-label">email:</label>
                    <input type="text" class="form-control" id="email" placeholder="email" aria-label="email">
                </div>
                <div class="col">
                    <label for="comment" class="form-label">comment:</label>
                    <input type="text" class="form-control" id="comment" placeholder="comment" aria-label="comment">
                </div>
                <div class="col">
                    <label for="anonim" class="form-label">Anonim:</label>
                    <input type="number" class="form-control" id="anonim" placeholder="anonim" aria-label="anonim">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="unique-number" class="form-label">unique-number:</label>
                    <input type="number" class="form-control" id="unique-number" placeholder="unique-number" aria-label="unique-number">
                </div>
                <div class="col">
                    <label for="nominal" class="form-label">nominal:</label>
                    <input type="number" class="form-control" id="nominal" placeholder="nominal" aria-label="nominal">
                </div>
                <div class="col">
                    <label for="main-donate" class="form-label">main-donate:</label>
                    <input type="number" class="form-control" id="main-donate" placeholder="main-donate" aria-label="main-donate">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="info-donate" class="form-label">info-donate:</label>
                    <input type="text" class="form-control" id="info-donate" placeholder="info-donate" aria-label="info-donate">
                </div>
                <div class="col">
                    <label for="cs-id" class="form-label">cs-id:</label>
                    <input type="number" class="form-control" id="cs-id" placeholder="cs-id" aria-label="cs-id">
                </div>
                <div class="col">
                    <label for="created-at" class="form-label">created-at:</label>
                    <input type="text" class="form-control" id="created-at" placeholder="created-at" aria-label="created-at">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="utm-source" class="form-label">utm-source:</label>
                    <input type="text" class="form-control" id="utm-source" placeholder="utm-source" aria-label="utm-source">
                </div>
                <div class="col">
                    <label for="utm-medium" class="form-label">utm-medium:</label>
                    <input type="text" class="form-control" id="utm-medium" placeholder="utm-medium" aria-label="utm-medium">
                </div>
                <div class="col">
                    <label for="utm-campaign" class="form-label">utm-campaign:</label>
                    <input type="text" class="form-control" id="utm-campaign" placeholder="utm-campaign" aria-label="utm-campaign">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="utm-term" class="form-label">utm-term:</label>
                    <input type="text" class="form-control" id="utm-term" placeholder="utm-term" aria-label="utm-term">
                </div>
                <div class="col">
                    <label for="utm-content" class="form-label">utm-content:</label>
                    <input type="text" class="form-control" id="utm-content" placeholder="utm-content" aria-label="utm-content">
                </div>
                <div class="col">
                    <label for="user-agent" class="form-label">user-agent:</label>
                    <input type="text" class="form-control" id="user-agent" placeholder="user-agent" aria-label="user-agent">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="country" class="form-label">country:</label>
                    <input type="text" class="form-control" id="country" placeholder="country" aria-label="country">
                </div>
                <div class="col">
                    <label for="city" class="form-label">city:</label>
                    <input type="text" class="form-control" id="city" placeholder="city" aria-label="city">
                </div>
                <div class="col">
                    <label for="provider" class="form-label">provider:</label>
                    <input type="text" class="form-control" id="provider" placeholder="provider" aria-label="provider">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="operating-system" class="form-label">operating-system:</label>
                    <input type="text" class="form-control" id="operating-system" placeholder="operating-system" aria-label="operating-system">
                </div>
                <div class="col">
                    <label for="ip-address" class="form-label">ip-address:</label>
                    <input type="text" class="form-control" id="ip-address" placeholder="ip-address" aria-label="ip-address">
                </div>
                <div class="col">
                    <label for="browser" class="form-label">browser:</label>
                    <input type="text" class="form-control" id="browser" placeholder="browser" aria-label="browser">
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <label for="mobdesk" class="form-label">mobdesk:</label>
                    <input type="text" class="form-control" id="mobdesk" placeholder="mobdesk" aria-label="mobdesk">
                </div>
            </div>
        </div>
        <div class="container button" style="margin-top: 15px;">
            <div class="row">
                <div class="col">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary" id="submit-josh">Insert</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#submit-josh').click(function(){
                    let campaign = $('#campaign-id').val();
                    let invoice = $('#invoice-id').val();
                    let repeatnew = $('#repeatnew').val();
                    let userId = $('#user-id').val();
                    let name = $('#name').val();
                    let whatsapp = $('#whatsapp').val();
                    let email = $('#email').val();
                    let comment = $('#comment').val();
                    let anonim = $('#anonim').val();
                    let uniqueNumber = $('#unique-number').val();
                    let nominal = $('#nominal').val();
                    let mainDonate = $('#main-donate').val();
                    let infoDonate = $('#info-donate').val();
                    let csId = $('#cs-id').val();
                    let createdAt = $('#created-at').val();
                    let utmSource = $('#utm-source').val();
                    let utmMedium = $('#utm-medium').val();
                    let utmCampaign = $('#utm-campaign').val();
                    let utmTerm = $('#utm-term').val();
                    let utmContent = $('#utm-content').val();
                    let userAgent = $('#user-agent').val();
                    let country = $('#country').val();
                    let city = $('#city').val();
                    let provider = $('#provider').val();
                    let operatingSystem = $('#operating-system').val();
                    let ipAddress = $('#ip-address').val();
                    let browser = $('#browser').val();
                    let mobdesk = $('#mobdesk').val();

                    var myarray = {
                        campaign_id: campaign,
                        invoice_id: invoice,
                        repeatnew: repeatnew,
                        user_id: userId,
                        name: name,
                        whatsapp: whatsapp,
                        email: email,
                        comment: comment,
                        anonim: anonim,
                        unique_number: uniqueNumber,
                        nominal: nominal,
                        main_donate: mainDonate,
                        info_donate: infoDonate,
                        cs_id: csId,
                        created_at: createdAt,
                        utm_source: utmSource,
                        utm_medium: utmMedium,
                        utm_campaign: utmCampaign,
                        utm_term: utmTerm,
                        utm_content: utmContent,
                        user_agent: userAgent,
                        country: country,
                        city: city,
                        provider: provider,
                        operating_system: operatingSystem,
                        ip_address: ipAddress,
                        browser: browser,
                        mobdesk: mobdesk

                    };
                    var data = {
                        "action": "joshfunction_manual_submitdonasi",
                        "datanya": myarray
                    };

                    $.post("<?php echo home_url('/wp-admin/admin-ajax.php') ?>", data,
                        function (response) {
                            response = response.replace("\n","");
                            console.log(response);
                            var beauty = JSON.parse(response);
                            if (beauty.statusDb == true) {
                                swal("Status AJAX", "Selamat! Insert DB telah berhasil :)", "success");
                            } else {
                                swal("Status AJAX", "Maaf! Insert DB GAGAL!", "error");
                            }
                            console.log('status tele: '+beauty.statusTele);
                            console.log('status email: '+beauty.statusEmail);
                        });
                    console.log(myarray);
                });
            });
        </script>
<?php }
?>