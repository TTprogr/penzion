<div class="menu">
    <ul>
        <?php
        foreach ($poleStranek AS $stranka) {
            if ($stranka->getMenu() == "") {
                continue;
            }

            echo "<li>
                <a href='?stranka={$stranka->getId()}'>
                    {$stranka->getMenu()}
                </a>
            </li>";
        }
        ?>
    </ul>
</div>