import "./state.css";
import { useState } from "react";

export function Contador() {

    const [contador, setContador] = useState(0) 
    
    function adicionarContador() {
        setContador( contador + 1 );
    }

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