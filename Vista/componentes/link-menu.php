
    <form action="" method="POST" >
        <input type="hidden" name="menu-item" value="<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>">
        <button class="py-2 px-2 hover:bg-[#DFD3C3] cursor-pointer rounded <?php if( isset($_SESSION['menu-item']) &&  $_SESSION['menu-item'] == htmlspecialchars($item, ENT_QUOTES, 'UTF-8')){ echo "bg-[#DFD3C3]";  } ?>" type="submit" class="cursor-pointer" >
            <?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>
        </button>
    </form>




<!-- if( $_SERVER["REQUEST_METHOD"] == "POST" && htmlspecialchars($item, ENT_QUOTES, 'UTF-8') == $_SESSION['menu-item'] ){ echo "bg-[#DFD3C3]" ;}elseif(htmlspecialchars($item, ENT_QUOTES, 'UTF-8') == 'Login'){ echo "bg-[#c3ac8c]"; } -->