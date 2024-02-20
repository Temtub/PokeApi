
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        
        <h1>Pokemon Api</h1>
        <?php
            $pokemonData = false;
            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                $numberOfPokemons = $_POST['number'];
                
                $pokemonData = cargarPokemons("https://pokeapi.co/api/v2/pokemon/");
            }
        ?>
        
        <form action="<?php $_SERVER['PHP_SELF']?>" method="POST">
            <p>Número de pokémons a cargar</p>
            <input type="number" max="40" name="number" value="<?php if(isset($_POST['number'])) echo $_POST['number']?>">
            <input type="submit">
        </form>
        
        <div>
            <?php
                if($pokemonData){
                    if($numberOfPokemons > 20){
                        $counter = 0;
                        $newData = cargarPokemons($pokemonData['next']);
                        
                        $pokemonData['results'] = array_merge( $pokemonData['results'], $newData['results'] );
                    }
                    echo '<form method="POST" action="'.$_SERVER['PHP_SELF'].'"><select name="pokemon" value="'. (isset($_POST['number']) ? $_POST['number']: "") .'">';
                    
                    foreach ($pokemonData['results'] as $pokemon){
                        if($counter >= $numberOfPokemons){
                            break;
                        }
                        $url = $pokemon['url'];
                        // Dividir la URL en partes usando la barra como delimitador
                        $url_parts = explode("/", $url);
                        
                        // Obtener el último elemento del array que contiene el ID
                        $id_part = $url_parts[6];
                        echo '<option value="'.$id_part.'">Pokemon: '. $pokemon['name'] .' - Id: '. $id_part .'</option>';
                        $counter++;
                    }
                    echo '</select>
                    <input type="hidden" name="number" value="'.$numberOfPokemons.'"> 
                    <input type="submit"></form>';
                }
            ?>
        </div>
        <div>
            <?php
            
            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                if(isset($_POST['pokemon'])){
                    $pokemon = cargarPokemons('https://pokeapi.co/api/v2/pokemon/'.$_POST['pokemon'].'/');
                    
                    foreach ($pokemon['forms'] as $pok){
                        $name = $pok['name'];
                    }
                    echo '<h2>'.$name.'</h2>';
                    
                    echo '<img src="'.$pokemon['sprites']['front_default'].'">';
                }
            }
            ?>
        </div>
        <?phpcurl_close($curl);?>
    </body>
</html>

<?php
    function cargarPokemons($url){
        //Hacemos la peticion 
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            
            $response = curl_exec($curl);
            
            if ($response === false) {
                // Manejo de errores
                echo "Error al obtener la información del Pokémon.";
            } 
            
            else {
                // Decodificar la respuesta JSON
                return json_decode($response, true);
                
            }
    }
?>
