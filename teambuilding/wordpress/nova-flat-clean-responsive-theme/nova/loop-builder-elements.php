<?php
global $GummTemplateBuilder;

$builderRowClass        = 'bluebox-builder-row';
$wasLastElementHeading  = false;
$currentWidthRatio      = 0;

$elementsEnabled    = $GummTemplateBuilder->getTemplateElementsEnabled('content');
$elementsCount      = count($elementsEnabled);

$counter = 1;

foreach ($elementsEnabled as $n => $element) {
    ob_start();
    $element->render();
    $elementHtml = ob_get_clean();
    
    if (!$elementHtml) {
        $elementsCount--;
        continue;
    }
    $currBuilderRowClass    = $builderRowClass;
    $additionalClass        = $wasLastElementHeading ? 'no-margin' : '';
    
    if ($currentWidthRatio == 0) {
        if ($counter === $elementsCount) {
            $currBuilderRowClass .= ' last-row';
            if (is_a($element, 'TwitterTweetsLayoutElement') && $element->widthRatio() == 1) {
                $currBuilderRowClass .= ' no-margin';
            }
        } elseif ($element->noMargin === true) {
            $currBuilderRowClass .= ' no-margin';
        }
        
        // open new row
        echo '<div class="' . $currBuilderRowClass . '">' . "\n";
        echo '<div class="row-fluid bluebox-container">' . "\n";
    }
    
    $currentWidthRatio += $element->widthRatio();
    
    if ($currentWidthRatio > 1) {
        // close the row
        echo '</div>' . "\n";
        echo '</div>' . "\n";
        
        $currentWidthRatio = $element->widthRatio();
        if ($counter === $elementsCount) {
            $currBuilderRowClass .= ' last-row';
            if (is_a($element, 'TwitterTweetsLayoutElement') && $currentWidthRatio == 1) {
                $currBuilderRowClass .= ' no-margin';
            }
        }
        
        // open new row
        echo '<div class="' . $currBuilderRowClass . '">' . "\n";
        echo '<div class="row-fluid bluebox-container">' . "\n";
        
    }
    
    echo $elementHtml;
    
    // If width ratio reached 1, i.e. elements filled one full row:
    if ($currentWidthRatio == 1) {
        // close the row
        echo '</div>' . "\n";
        echo '</div>' . "\n";
        
        // reset width ratio counter
        $currentWidthRatio = 0;
    }
    
    $wasLastElementHeading = $element instanceof HeadingLayoutElement;
    
    $counter++;
}

if ($currentWidthRatio != 0) {
    // close the row
    echo '</div>' . "\n";
    echo '</div>' . "\n";
}

?>