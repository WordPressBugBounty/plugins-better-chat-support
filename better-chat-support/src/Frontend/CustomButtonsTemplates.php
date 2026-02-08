<?php

namespace ThemeAtelier\BetterChatSupport\Frontend;

class CustomButtonsTemplates
{
   public static $getData;

   function __construct(array $args)
   {
      self::$getData = $args;
   }
   // Template Style 1
   public function mcs_button_s1()
   {
      $iterateData = self::$getData;
      $atts = $iterateData;

      // button settings
      $fbid = $atts['fbid'];
      $sizes = $atts['sizes'];
      $label = $atts['label'];
      $visibility = $atts['visibility'];
      $rounded = $atts['rounded'] === 'yes' ? 'rounded' : '';
      $background = $atts['background'] === 'yes' ? 'mSupport-btn-bg' : '';
      $icon = isset($atts['icon']) ? $atts['icon'] : '';
      $classes = 'mSupport-button-2 ' . $sizes . ' ' . $visibility . ' ' . $rounded . ' ' . $background;

      $agentPhoto = $atts['photo'];
      $agentName = $atts['name'];
      $agentDesignation = $atts['designation'];
      $onlineText = $atts['online'];
      $offlineText = $atts['offline'];
      // availablity
      $avlTimezone =  $atts['timezone'];
      $avlSunday = $atts['sunday'];
      $avlMonday = $atts['monday'];
      $avlTuesday = $atts['tuesday'];
      $avlWednesday = $atts['wednesday'];
      $avlThursday = $atts['thursday'];
      $avlFriday = $atts['friday'];
      $avlSaturday = $atts['saturday'];
?>
      <div class="button-wrapper">
         <div <?php if ($avlTimezone) { ?> data-timezone="<?php echo esc_attr($avlTimezone); ?>" <?php } ?> class="mSupportButtons mSupport-button-1 <?php echo esc_attr($classes); ?> avatar-active" data-btnavailablety='{ "sunday":"<?php echo esc_attr($avlSunday); ?>", "monday":"<?php echo esc_attr($avlMonday); ?>", "tuesday":"<?php echo esc_attr($avlTuesday); ?>", "wednesday":"<?php echo esc_attr($avlWednesday); ?>", "thursday":"<?php echo esc_attr($avlThursday); ?>", "friday":"<?php echo esc_attr($avlFriday); ?>", "saturday":"<?php echo esc_attr($avlSaturday); ?>" }'>
            <?php if ($agentPhoto && $icon == 'yes') { ?>
               <img src="<?php echo esc_attr($agentPhoto); ?>" />
            <?php } ?>

            <div class="info-wrapper">
               <?php if ($agentName || $agentDesignation) { ?>
                  <div class="info"><?php if ($agentName) { ?><?php echo esc_html($agentName); ?><?php } ?> <?php if ($agentDesignation) { ?>/ <?php echo esc_html($agentDesignation); ?><?php } ?></div>
               <?php } ?>
               <?php if ($label) { ?>
                  <div class="title"><?php echo esc_html($label); ?></div>
               <?php } ?>
               <?php if ($onlineText) { ?>
                  <div class="online"><?php echo esc_html($onlineText); ?></div>
               <?php } ?>
               <?php if ($offlineText) { ?>
                  <div class="offline"><?php echo esc_html($offlineText); ?></div>
               <?php } ?>
            </div>
            <a href="https://www.m.me/<?php echo esc_attr($fbid); ?>" target="_blank"></a>
         </div>
      </div>
   <?php
   }

   // Template style 2
   function mcs_button_s2()
   {
      $iterateData = self::$getData;
      $atts = $iterateData;
      $fbid = $atts['fbid'];
      $sizes = $atts['sizes'];
      $label = $atts['label'];
      $visibility = $atts['visibility'];
      $rounded = $atts['rounded'] === 'yes' ? 'rounded' : '';
      $background = $atts['background'] === 'yes' ? 'mSupport-btn-bg' : '';
      $icon_bg = $atts['icon_bg'] === 'yes' ? 'icon_bg' : '';
      $icon = isset($atts['icon']) ? $atts['icon'] : '';
      $classes = 'mSupport-button-2 ' . $sizes . ' ' . $visibility . ' ' . $rounded . ' ' . $background . ' ' . $icon_bg;
   ?>
      <div class="button-wrapper">
         <a href="https://www.m.me/<?php echo esc_attr($fbid); ?>" class="<?php echo esc_attr($classes); ?>">
            <?php if ($icon == 'yes') { ?><i class="icofont-facebook-messenger"></i><?php } ?><?php echo esc_html($label); ?>
         </a>
      </div>
<?php
   }
} // End Class