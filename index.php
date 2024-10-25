<?php
include 'db.php';  // Connexion à la base de données

// Récupération de la catégorie à filtrer (si spécifiée) et du texte de recherche
$categorie_filter = isset($_GET['categorie']) ? intval($_GET['categorie']) : 0;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Requête SQL avec le filtre de catégorie et la recherche (si spécifiés)
$sql = "SELECT recettes.*, categories.nom AS categorie FROM recettes 
        JOIN categories ON recettes.categorie_id = categories.id WHERE 1";

// Ajout du filtre de catégorie
if ($categorie_filter > 0) {
    $sql .= " AND recettes.categorie_id = :categorie";
}

// Ajout du filtre de recherche
if (!empty($search)) {
    $sql .= " AND recettes.nom LIKE :search";
}

$sql .= " ORDER BY recettes.id DESC";
$stmt = $conn->prepare($sql);

// Préparer les paramètres de la requête
$params = [];
if ($categorie_filter > 0) {
    $params['categorie'] = $categorie_filter;
}
if (!empty($search)) {
    $params['search'] = '%' . $search . '%';
}

$stmt->execute($params);
$recettes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site de Recettes</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Styles pour la pop-up */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1000; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close-button {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close-button:hover,
        .close-button:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="my-4 text-center">Site de Recettes</h1>

        <!-- Formulaire de recherche -->
        <form class="form-inline mb-4 justify-content-center" action="index.php" method="get">
            <input class="form-control mr-sm-2" type="search" name="search" placeholder="Rechercher une recette" value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-outline-success" type="submit">Rechercher</button>
            
            <!-- Inclure la catégorie sélectionnée dans le formulaire de recherche -->
            <?php if ($categorie_filter > 0): ?>
                <input type="hidden" name="categorie" value="<?php echo $categorie_filter; ?>">
            <?php endif; ?>
        </form>

        <!-- Boutons de filtre par catégorie -->
        <div class="btn-group mb-4 d-flex justify-content-center" role="group">
            <a href="index.php?search=<?php echo htmlspecialchars($search); ?>" class="btn btn-outline-secondary <?php echo $categorie_filter == 0 ? 'active' : ''; ?>">Afficher tout</a>
            <a href="index.php?categorie=1&search=<?php echo htmlspecialchars($search); ?>" class="btn btn-outline-secondary <?php echo $categorie_filter == 1 ? 'active' : ''; ?>">Chinoise</a>
            <a href="index.php?categorie=2&search=<?php echo htmlspecialchars($search); ?>" class="btn btn-outline-secondary <?php echo $categorie_filter == 2 ? 'active' : ''; ?>">Japonaise</a>
            <a href="index.php?categorie=3&search=<?php echo htmlspecialchars($search); ?>" class="btn btn-outline-secondary <?php echo $categorie_filter == 3 ? 'active' : ''; ?>">Thaïlandaise</a>
            <a href="index.php?categorie=4&search=<?php echo htmlspecialchars($search); ?>" class="btn btn-outline-secondary <?php echo $categorie_filter == 4 ? 'active' : ''; ?>">Vietnamienne</a>
        </div>

        <!-- Liste des recettes -->
        <div class="row">
            <?php foreach ($recettes as $recette): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($recette['nom']); ?></h5>
                        <button class="btn btn-primary view-recipe" data-recipe-id="<?php echo $recette['id']; ?>">Voir la recette</button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Pop-up de recette -->
    <div id="recipe-modal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2 id="recipe-title"></h2>
            <h4>Ingrédients :</h4>
            <ul id="recipe-ingredients"></ul>
            <h4>Étapes de préparation :</h4>
            <ol id="recipe-steps"></ol>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // JavaScript pour gérer la pop-up
        $(document).ready(function() {
            // Lorsque vous cliquez sur "Voir la recette"
            $('.view-recipe').click(function() {
                var recipeId = $(this).data('recipe-id');
                
                // Requête AJAX pour récupérer les informations de la recette
                $.ajax({
                    url: 'get_recipe.php', // Remplacez par le fichier PHP qui récupère les recettes
                    type: 'GET',
                    data: { id: recipeId },
                    success: function(response) {
                        var recipe = JSON.parse(response);
                        
                        // Remplir la pop-up avec les données
                        $('#recipe-title').text(recipe.nom);
                        $('#recipe-ingredients').empty().append('<li>' + recipe.ingredients.replace(/,/g, '</li><li>') + '</li>');
                        $('#recipe-steps').empty().append('<li>' + recipe.etapes.replace(/\./g, '</li><li>') + '</li>');

                        // Afficher la pop-up
                        $('#recipe-modal').show();
                    },
                    error: function() {
                        alert('Erreur lors de la récupération des données de la recette.');
                    }
                });
            });

            // Fermer la pop-up
            $('.close-button').click(function() {
                $('#recipe-modal').hide();
            });
        });
    </script>
</body>
</html>
