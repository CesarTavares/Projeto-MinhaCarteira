    <footer>
        <h5>&copy; 2024 Minha Carteira. Todos os direitos reservados.</h5>        
    </footer>
    
    <?php if (isset($additionalJS)): ?>
        <?php foreach ($additionalJS as $js): ?>
            <script src="<?= $js ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <?php if (isset($customJS)): ?>
        <script><?= $customJS ?></script>
    <?php endif; ?>
</body>
</html>