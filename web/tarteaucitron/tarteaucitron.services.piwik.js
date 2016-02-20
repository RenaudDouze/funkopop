// piwik
tarteaucitron.services.piwik = {
    "key": "piwik",
    "type": "analytic",
    "name": "Piwik",
    "uri": "https://fr.piwik.org/",
    "needConsent": true,
    "cookies": ['_pk_ref', '_pk_cvar', '_pk_id', '_pk_ses'],
    "js": function () {
        var _paq = _paq || [];
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function() {
          var u="//piwik.renauddouze.fr/";
          _paq.push(['setTrackerUrl', u+'piwik.php']);
          _paq.push(['setSiteId', tarteaucitron.user.piwikId]);
          var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
          g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
        })();
    }
};