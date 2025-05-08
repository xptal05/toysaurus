<script src="https://widget.packeta.com/v6/www/js/library.js"></script>

<script>
    const packetaApiKey = '92283e7127768ce4';

    const packetaOptions = {
        country: "cz",
        language: "cs",
        valueFormat: "place,street,city",
        view: "modal",
        vendors: [{
                country: "cz",
                currency: "CZK"
            },
            {
                country: "cz",
                group: "zbox",
                currency: "CZK"
            }
        ],
        defaultCurrency: "CZK"
    };


    // function that shows the selected point location
    function showSelectedPickupPoint(point) {
        const saveElement = document.querySelector(".packeta-selector-value");
        const pointIdInput = document.getElementById('packetery_point_id')
        // Add here an action on pickup point selection
        saveElement.innerText = '';
        if (point) {
            console.log("Selected point", point);
            console.log("Point ID: ",point["id"])
            pointIdInput.value = point["id"]
            saveElement.innerText = "Address: " + point.formatedValue;
        }
    }
</script>

