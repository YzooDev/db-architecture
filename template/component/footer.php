<?php
/** @var string $templateScript */
?>
<footer class="footer">
    <div class="container footer-inner">
        <div class="footer-column">
            <p>© <?= date('Y') ?> Daniel Bezes Architecture</p> 
            <p>Peyrole, France</p>
        </div>
        <div class="footer-column">
            <p>Mail : daniel.bezes@free.fr</p>
            <p>Téléphone : 06.81.23.89.23</p>
            <a href="https://www.linkedin.com/in/daniel-bezes-1898276a"  target="_blank">LinkedIn : Daniel Bezes</a>
        </div>
        <div class="footer-column">       
            <a href="/terms">Mentions légales et CGU</a>
            <p>Tous droits réservés</p>     
        </div>
    </div>
</footer>
<?php if (isset($templateScript) && $templateScript !== null) : ?>
    <script src="/assets/script/<?= htmlspecialchars($template ?? '') ?>.js"></script>
<?php endif; ?>
</body>
</html>
