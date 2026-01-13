import "./state.css";
import { useState } from "react";

// Exemplo de uso do useState por meio do componente "Contador"
export function Contador() {

    // Aqui é definido a variável e o setter da variável, definindo também o valor padrão da variável
    const [contador, setContador] = useState(0) 
    
    // Função responsável por utilizar o setter
    function adicionarContador() {
        setContador( contador + 1 );
    }

    // Por fim, o retorno do componente, contendo o botão que vai chamar a função que utiliza o setter
    return (
        <div>
            <div className="linha">
                <p>Contador</p>
                <div>{ contador }</div>
            </div>
            <button onClick={ adicionarContador }>Adicionar</button>
        </div>
    );
}