    <div id="modalOverlay" class="modal-overlay">
        <div class="modal-content">
            <span class="close-modal">CLOSE</span>
            <div class="response"></div>
        </div>
    </div>

</script><script src="include/jquery.mask.min.js"></script>
<script src="include/validateEmail.min.js"></script>
<script src="include/form.js"></script> 
<? // Facebook Pixel Code
if (!empty($fbp) and $ip !='127.0.0.1') { ?>
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '<?= $fbp ?>');
  fbq('track', 'PageView');
</script>
<noscript>
  <img height="1" width="1" style="display:none" 
       src="https://www.facebook.com/tr?id=<?= $fbp ?>&ev=PageView&noscript=1"/>
</noscript>
<? } 
// Google tag (gtag.js)
if (!empty($ggl) and $ip != '127.0.0.1') { ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= $ggl ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '<?= $ggl ?>');
</script>
<? } ?>