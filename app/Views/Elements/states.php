<select name="state" class="form-control input-sm">
    <?php if ( empty($state) ) {
        $state = null;
        ?>
        <option value="" selected>Select State</option>
    <?php } ?>
    <option value="AL" <?php if($state=='AL'){echo 'SELECTED';} ?>>Alabama</option>
    <option value="AK" <?php if($state=='AK'){echo 'SELECTED';} ?>>Alaska</option>
    <option value="AZ" <?php if($state=='AZ'){echo 'SELECTED';} ?>>Arizona</option>
    <option value="AR" <?php if($state=='AR'){echo 'SELECTED';} ?>>Arkansas</option>
    <option value="CA" <?php if($state=='CA'){echo 'SELECTED';} ?>>California</option>
    <option value="CO" <?php if($state=='CO'){echo 'SELECTED';} ?>>Colorado</option>
    <option value="CT" <?php if($state=='CT'){echo 'SELECTED';} ?>>Connecticut</option>
    <option value="DE" <?php if($state=='DE'){echo 'SELECTED';} ?>>Delaware</option>
    <option value="DC" <?php if($state=='DC'){echo 'SELECTED';} ?>>District of Columbia</option>
    <option value="FL" <?php if($state=='FL'){echo 'SELECTED';} ?>>Florida</option>
    <option value="GA" <?php if($state=='GA'){echo 'SELECTED';} ?>>Georgia</option>
    <option value="HI" <?php if($state=='HI'){echo 'SELECTED';} ?>>Hawaii</option>
    <option value="ID" <?php if($state=='ID'){echo 'SELECTED';} ?>>Idaho</option>
    <option value="IL" <?php if($state=='IL'){echo 'SELECTED';} ?>>Illinois</option>
    <option value="IN" <?php if($state=='IN'){echo 'SELECTED';} ?>>Indiana</option>
    <option value="IA" <?php if($state=='IA'){echo 'SELECTED';} ?>>Iowa</option>
    <option value="KS" <?php if($state=='KS'){echo 'SELECTED';} ?>>Kansas</option>
    <option value="KY" <?php if($state=='KY'){echo 'SELECTED';} ?>>Kentucky</option>
    <option value="LA" <?php if($state=='LA'){echo 'SELECTED';} ?>>Louisiana</option>
    <option value="ME" <?php if($state=='ME'){echo 'SELECTED';} ?>>Maine</option>
    <option value="MD" <?php if($state=='MD'){echo 'SELECTED';} ?>>Maryland</option>
    <option value="MA" <?php if($state=='MA'){echo 'SELECTED';} ?>>Massachusetts</option>
    <option value="MI" <?php if($state=='MI'){echo 'SELECTED';} ?>>Michigan</option>
    <option value="MN" <?php if($state=='MN'){echo 'SELECTED';} ?>>Minnesota</option>
    <option value="MS" <?php if($state=='MS'){echo 'SELECTED';} ?>>Mississippi</option>
    <option value="MO" <?php if($state=='MO'){echo 'SELECTED';} ?>>Missouri</option>
    <option value="MT" <?php if($state=='MT'){echo 'SELECTED';} ?>>Montana</option>
    <option value="NE" <?php if($state=='NE'){echo 'SELECTED';} ?>>Nebraska</option>
    <option value="NV" <?php if($state=='NV'){echo 'SELECTED';} ?>>Nevada</option>
    <option value="NH" <?php if($state=='NH'){echo 'SELECTED';} ?>>New Hampshire</option>
    <option value="NJ" <?php if($state=='NJ'){echo 'SELECTED';} ?>>New Jersey</option>
    <option value="NM" <?php if($state=='NM'){echo 'SELECTED';} ?>>New Mexico</option>
    <option value="NY" <?php if($state=='NY'){echo 'SELECTED';} ?>>New York</option>
    <option value="NC" <?php if($state=='NC'){echo 'SELECTED';} ?>>North Carolina</option>
    <option value="ND" <?php if($state=='ND'){echo 'SELECTED';} ?>>North Dakota</option>
    <option value="OH" <?php if($state=='OH'){echo 'SELECTED';} ?>>Ohio</option>
    <option value="OK" <?php if($state=='OK'){echo 'SELECTED';} ?>>Oklahoma</option>
    <option value="OR" <?php if($state=='OR'){echo 'SELECTED';} ?>>Oregon</option>
    <option value="PA" <?php if($state=='PA'){echo 'SELECTED';} ?>>Pennsylvania</option>
    <option value="RI" <?php if($state=='RI'){echo 'SELECTED';} ?>>Rhode Island</option>
    <option value="SC" <?php if($state=='SC'){echo 'SELECTED';} ?>>South Carolina</option>
    <option value="SD" <?php if($state=='SD'){echo 'SELECTED';} ?>>South Dakota</option>
    <option value="TN" <?php if($state=='TN'){echo 'SELECTED';} ?>>Tennessee</option>
    <option value="TX" <?php if($state=='TX'){echo 'SELECTED';} ?>>Texas</option>
    <option value="UT" <?php if($state=='UT'){echo 'SELECTED';} ?>>Utah</option>
    <option value="VT" <?php if($state=='VT'){echo 'SELECTED';} ?>>Vermont</option>
    <option value="VA" <?php if($state=='VA'){echo 'SELECTED';} ?>>Virginia</option>
    <option value="WA" <?php if($state=='WA'){echo 'SELECTED';} ?>>Washington</option>
    <option value="WV" <?php if($state=='WV'){echo 'SELECTED';} ?>>West Virginia</option>
    <option value="WI" <?php if($state=='WI'){echo 'SELECTED';} ?>>Wisconsin</option>
    <option value="WY" <?php if($state=='WY'){echo 'SELECTED';} ?>>Wyoming</option>
</select>