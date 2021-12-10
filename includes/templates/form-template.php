<?php
$attributes = get_query_var( 'simple_crm_form_attributes' );
?>

<div class="simple-crm-main-form-wrapper">
    <form action="" class="simple-crm-main-form">
        <?php wp_nonce_field( 'simple-crm-submit-form', 'nonce' ); ?>
        <div class="simple-crm-row">
            <label for="name"><?php echo esc_html( $attributes['name_label'] ) ?></label>
            <input type="text" required id="name" name="data[name]" maxlength="<?php echo esc_attr( $attributes['name_length'] ) ?>">
        </div>
        <div class="simple-crm-row">
            <label for="phone"><?php echo esc_html( $attributes['phone_label'] ) ?></label>
            <input type="number" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" required id="phone" name="data[phone]" maxlength="<?php echo esc_attr( $attributes['phone_length'] ) ?>">
        </div>
        <div class="simple-crm-row">
            <label for="email"><?php echo esc_html( $attributes['email_label'] ) ?></label>
            <input type="email" required id="email" name="data[email]" maxlength="<?php echo esc_attr( $attributes['email_length'] ) ?>">
        </div>
        <div class="simple-crm-row">
            <label for="desire_budget"><?php echo esc_html( $attributes['desire_budget_label'] ) ?></label>
            <input type="number" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" required id="desire_budget" name="data[desire_budget]" maxlength="<?php echo esc_attr( $attributes['desire_budget_length'] ) ?>">
        </div>
        <div class="simple-crm-row">
            <label for="message"><?php echo esc_html( $attributes['message_label'] ) ?></label>
            <textarea name="data[message]" id="message" cols="<?php echo esc_attr( $attributes['message_columns'] ) ?>" rows="<?php echo esc_attr( $attributes['message_rows'] ) ?>" maxlength="<?php echo esc_attr( $attributes['message_length'] ) ?>"></textarea>
        </div>
        <input type="hidden" name="data[current_time]" value="<?php echo esc_html( $attributes['current_time'] ) ?>">
        <div class="simple-crm-row">
            <button type="submit" class="simple-crm-main-form-submit-button"><?php echo esc_html__( 'Submit', 'simple-crm' ); ?></button>
        </div>
        <div class="simple-crm-main-form-result">

        </div>
    </form>
</div>
