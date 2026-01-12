import { useNavigate, Link } from "react-router-dom";
import "./header.css";

export function Header() {

    const navigate = useNavigate();
    
    function navegar(pag, titulo = null, descricao = null) {

        if(pag === "/pag_params") {

            const query = new URLSearchParams();

            query.set("titulo", titulo);
            query.set("descricao", descricao);

            navigate(`${ pag }?${ query.toString() }`);
        }
        else {
            navigate(pag);
        }
    }

    // Existem duas formas de fazer esse redirecionamento, usando o useNavigate e usando o link, ambos do react-router-dom
    // Vou fazer com um de cada
    return (
        <header className="headerContainer">
            <ul>
                <li><button onClick={() => navegar("/") }>Home - onNavigate</button></li>
                <li><Link to="/" ><button>Home - Link</button></Link></li>
                <li><button onClick={() => navegar("/app") }>App</button></li>
                <li><button onClick={() => navegar("/pag_params", "Titulo aleatorio", "Descrição aleatoria só pra gerar engajamento") }>Página com Params</button></li>
                <li><Link to="/consumo_api" ><button>Consumir API</button></Link></li>
            </ul>
        </header>
    )
}