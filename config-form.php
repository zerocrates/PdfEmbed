<?php $view = get_view(); ?>
<div id="pdf-embed-settings">
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel('height', __('Height')); ?>
        </div>
        <div class="inputs five columns omega">
            <p class="explanation">
                <?php
                echo __(
                    'Height of the embedded PDF viewer, in pixels. Width will automatically adjust '
                    . 'to fit the surrounding content.'
                );
                ?>
            </p>
            <?php echo $view->formText('height', $settings['height']); ?>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $view->formLabel('pdf_embed_type', __('Embed Type')); ?>
        </div>
        <div class="inputs five columns omega">
            <p class="explanation">
                <?php
                echo __(
                    'Choose the method to use to embed PDF documents. "Object" uses the browser\'s native '
                    . 'PDF viewer or plugins like Adobe Reader. "PDF.js" uses a cross-browser '
                    . 'JavaScript-based PDF viewer.'
                );
                ?>
            </p>
            <?php
            echo $view->formSelect('pdf_embed_type', $settings['pdf_embed_type'], array(), array(
                'object' => 'Object',
                'pdf_js' => 'PDF.js'
            ));
            ?>
        </div>
    </div>
</div>
