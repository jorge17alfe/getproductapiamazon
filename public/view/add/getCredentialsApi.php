<div id="alocraise1">
    <div class="py-3">
        <!--    <div class="pb-2">
            <h1 class="text-center"> <?= get_admin_page_title() ?></h1>
        </div>-->
        <div class="">
            <h3></h3>
        </div>

    </div>

    <form class=" row g-2  my-3" id="formDataCredentials" novalidate>
        <input type="hidden" class="form-control form-control-sm" value="" id="id" name="id" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
        <div>
            <div class='input-group input-group-sm  mb-1 delete-item-Credentialsid'>
                <span class='input-group-text'>Partner Tag: </span>
                <input type='text' class='form-control form-control-sm' placeholder='Your id Credentials' id='partner_tag' name='credentials[partner_tag]' aria-label='Sizing example input' aria-describedby='inputGroup-sizing-sm'>
            </div>
            <div class='input-group input-group-sm  mb-1 delete-item-Credentialsid'>
                <span class='input-group-text'> Access Key: </span>
                <input type='text' class='form-control form-control-sm' placeholder='Your access key' id='access_key' name='credentials[access_key]' aria-label='Sizing example input' aria-describedby='inputGroup-sizing-sm'>
            </div>

            <div class='input-group input-group-sm  mb-1 delete-item-Credentialsid'>
                <span class='input-group-text'>Secret Key: </span>
                <input type='text' class='form-control form-control-sm' placeholder='Your secret key' id='secret_key' name='credentials[secret_key]' aria-label='Sizing example input' aria-describedby='inputGroup-sizing-sm'>
            </div>
            <div class='input-group input-group-sm   justify-content-around'>
                <a href="javascript:void(0)" id="btnResetCredentials" class="btn btn-danger ms-1 rounded" delete-credentials-id=''>Reset</a>
                <?php $compo->buttonSend("Save", "btnSendCredentials");  ?>

            </div>
        </div>


    </form>
</div>

<script>
    jQuery(document).ready(($) => {

        $("#btnSendCredentials").on("click", (e) => {
            e.preventDefault();

            $.ajax({

                method: "POST",
                url: PetitionAjax.url,
                data: {
                    action: "save_data_credentials_amazon_id",
                    nonce: PetitionAjax.security,
                    data: $("#formDataCredentials").serialize(),
                }

            }).done((response) => {
                response = response.substring(0, response.length - 1);
                response = JSON.parse(response);
                console.log(response)
                location.reload()

            })

        })

        const GetCredentialsIds = () => {

            $.get({
                url: PetitionAjax.url,
                data: {
                    action: "get_data_credentials_amazon_ids",
                    nonce: PetitionAjax.security,
                }
            }).done(response => {
                response = response.substring(0, response.length - 1);
                response = JSON.parse(response);
                console.log(response)
                if (!response) return

                for (k in response) {
                    $(`#${k}`).val(response[k])
                    if (k != "id") {
                        $(`#${k}`).attr({"disabled": "disabled"})
                        $(`#${k}`).after(` <a href="javascript:void(0) " onclick="update('${k}')" class="btn btn-outline-success ms-1 lock-unlock-${k} rounded"><i class="bi bi-lock"></i></a> `)
                    }
                }
                $(`#btnResetCredentials`).attr({
                    "delete-credentials-id": response['id']
                })
            })
        }
        GetCredentialsIds();

        $(document).on("click", "#btnResetCredentials", () => {

            deleteRow("delete-credentials-id", "delete_data_credentials_amazon_id", "")
            location.reload()
        })


    })

    const update = (k) => {
        jQuery(document).ready(($) => {
            if ($(`#${k}`).attr("disabled")) {
                $(`#${k}`).removeAttr("disabled")
                $(`.lock-unlock-${k}`).html(`<i class="bi bi-unlock"></i>`)
            } else {
                $(`.lock-unlock-${k}`).html(`<i class="bi bi-lock"></i>`)
                $(`#${k}`).attr("disabled", "disabled")

            }
        })
    }
</script>