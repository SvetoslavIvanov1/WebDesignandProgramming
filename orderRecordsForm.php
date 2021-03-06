<?php
ini_set("session.save_path", "/home/unn_w17004799/sessionData");
session_start();
?>




<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>Order Records</title>
    <link rel="stylesheet" type="text/css" href="index.css">

    <<ul>
        <li><a href="index.html">Home</a></li>
        <li><a href="Credits.html">Credits</a></li>
        <li><a href="orderRecordsForm.php">Order Records</a></li>
        <li><a href="Records.php">Records</a></li>
        <li><a href="Logout.php">Logout</a></li>
    </ul>
</head>
<body>

<?php
/* This code dynamically generates a web page containing a form designed with the html required to display one
checkbox for each of the records currently held in the nmc_records database table has been provided for you in the
assessment section for the module on blackboard. The user can select one or more records that they are interested in
ordering by clicking the checkboxes.
Use the browser to look at the structure of the html generated by the php code. */

$url = "http://unn-izge1.newnumyspace.co.uk/KF5002/assessment/orderRecordsFormInc.php";
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($curl);
curl_close($curl);
echo $result;
?>

<!-- Here you need to add Javascript or a link to a script (.js file) to process the form as required for the assignment -->








<script type="text/javascript">




    window.addEventListener('load', function()
    {

        "use strict";

        const form = document.getElementById("orderForm");
        const records = form.querySelectorAll('div.item');
        const recordsCount = records.length;
        const recordsCheckboxes = [recordsCount];
        for(let i = 0; i < recordsCount; i++)
        {
            recordsCheckboxes[i] = records[i].querySelector('input[type=checkbox]');
        }


        const collectionMethod = form.querySelectorAll('input[name=deliveryType]');
        const collectionMethodCount = collectionMethod.length;


        const termsText = document.getElementById('termsText');
        const termsCheckbox = termsText.querySelector('input[type=checkbox]');








        termsCheckbox.addEventListener('click', function () {

            if(termsCheckbox.checked)
            {
                termsText.style.color = "black";
                termsText.style.fontWeight = "normal";
            }
            else
            {
                termsText.style.color = "red";
                termsText.style.fontWeight = "bold";
            }

            editSubmitButton();
        });





        for(let i = 0; i < collectionMethodCount; i++)
        {
            collectionMethod[i].addEventListener('click', showPrices);
        }

        for(let i = 0; i < recordsCount; i++)
        {
            recordsCheckboxes[i].addEventListener('click', showPrices);
        }


        function editSubmitButton() {



            const submitButton = form.submit;


            let recordChecked = false;





            if(parseFloat(form.total.value) != 0)
            {
                recordChecked = true;
            }
            else
            {
                recordChecked = false;
            }



            if(termsCheckbox.checked)
            {

                if(termsCheckbox.checked &&  recordChecked)
                {
                    submitButton.disabled = false;
                }

                else
                {
                    submitButton.disabled = true;
                }
            }
            else
            {

                if(termsCheckbox.checked && recordChecked)
                {
                    submitButton.disabled = false;
                }
                else
                {
                    submitButton.disabled = true;
                }
            }

        }





        function showPrices(){
            let totalCost = 0;
            for(let i = 0; i < recordsCount; i++)
            {
                if(recordsCheckboxes[i].checked)
                {
                    totalCost += parseFloat(recordsCheckboxes[i].dataset.price);
                }
            }

            if(totalCost != 0)
            {
                for(let i = 0; i < collectionMethodCount; i++)
                {
                    if(collectionMethod[i].checked)
                    {
                        totalCost += parseFloat(collectionMethod[i].dataset.price);
                    }
                }
            }

            form.total.value = totalCost.toFixed(2) + " £";
            editSubmitButton();
        }

    });

</script>















</body>
</html>