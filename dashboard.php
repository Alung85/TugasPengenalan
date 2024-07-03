<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Selamat Datang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php

        if (file_exists("navbar.php")) {
            include("navbar.php");
        }
    ?>
    <div class="container mt-4 ">
        <h3 class="fst-italic" style="text-align: center;">Hola</h3>
        <h5 class="fst-italic" >Lorem ipsum dolor sit amet consectetur adipisicing elit. Sunt, facere? Recusandae officiis totam impedit at commodi! Ipsum aut optio ipsam enim? Ad, excepturi dolorum magnam quos facilis error dicta debitis.Lorem ipsum dolor sit amet consectetur, adipisicing elit. Veniam, dolores quia. Assumenda recusandae placeat aliquid voluptatem nam, excepturi voluptates provident aspernatur sunt fuga itaque voluptatibus libero dolor quam sit aliquam Lorem ipsum dolor sit amet consectetur adipisicing elit. Hic ipsa iste, minus corporis quisquam mollitia fugit, molestiae magnam optio neque labore. Dignissimos consequatur consectetur ea exercitationem est. Hic, alias corporis! Lorem ipsum, dolor sit amet consectetur adipisicing elit. Inventore, corporis possimus fugiat veritatis rem sit quisquam! Odit voluptates, quasi itaque, repellat odio est omnis neque molestiae quod deleniti veritatis repudiandae Lorem ipsum dolor sit amet consectetur adipisicing elit. Perferendis veniam ipsa ipsam magnam ea consequuntur, natus deleniti eaque minima, quo vitae error minus molestias. Ad exercitationem sed aspernatur repellendus qui? Lorem ipsum dolor, sit amet consectetur adipisicing elit. Iste autem, facere natus totam ducimus ea doloribus! Natus omnis ex facere, accusamus ut soluta non earum! Dolorum adipisci animi harum doloribus! Lorem ipsum dolor sit amet consectetur adipisicing elit. Optio aperiam doloremque temporibus facere in, deserunt ducimus laboriosam deleniti quisquam ratione quae magnam eius nihil molestias beatae minus numquam tenetur excepturi.</h5>
    </div>
    <?php
        if (file_exists("../footer.php")) {
            include("../footer.php");
        }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
