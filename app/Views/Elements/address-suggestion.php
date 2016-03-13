<?php if(isset($errors['suggestions'])): ?>
    <div class="alert alert-danger" role="alert">

        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
        <!--------dependency: web/js/address-suggestion.js------------>
        <span class="sr-only">Suggested Address:</span>
        Your address could not be verified. :(  The following is what we found:<br>
        <?php foreach($errors['suggestions'] as $key=> $address ): ?>
            <?=$address['full_address']?>&nbsp;<a href="#" class="suggestedAddress" data-address_1="<?=$address['street_number']?> <?=$address['street']?>"  data-city="<?=$address['city']?>"  data-state="<?=$address['state']?>" data-zip="<?=$address['zip_code']?>" >use</a><br>
        <?php endforeach; ?>

    </div>
<?php endif; ?>