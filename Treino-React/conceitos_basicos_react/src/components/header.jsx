import { useNavigate, Link } from "react-router-dom";
import "./header.css";

// Exemplo de uso de navegação de páginas via useNavigate e Link por meio do componente "Header"
export function Header() {

    // Para o useNavigate, é preciso criar uma constante com a função de navegação
    const navigate = useNavigate();
    
    // Aqui é criada a função que vai utilizar da constante "navigate" para navegar entre as páginas
    // Ela recebe 3 parametros: o caminho que ele deve ir, o titulo e a descrição, sendo esses dois opcionais (por isso o null)
    function navegar(pag, titulo = null, descricao = null) {

        // Se o caminho for esse, ele vai criar uma query com os outros dois parametros para navegar e passar dados
        if(pag === "/pag_params") {

            const query = new URLSearchParams();

            // Define as chaves e os valores da query
            query.set("titulo", titulo);
            query.set("descricao", descricao);

            // Por fim, navega passando o caminho e a query como parametro na url
            navigate(`${ pag }?${ query.toString() }`);
        }
        // Caso contrário, apenas navega normalmente
        else {
            navigate(pag);
        }
    }

    // Existem duas formas de fazer esse redirecionamento, usando o useNavigate e usando o link, ambos do react-router-dom
    // Vou fazer com um de cada
    return (
        <header className="headerContainer">
            <ul>
                ${
                //O jeito utilizando Link é muito mais direto e simples, pois ele literalmente cria uma tag "a" no html
                }
                <li><button onClick={() => navegar("/") }>Home - onNavigate</button></li>
                <li><Link to="/" ><button>Home - Link</button></Link></li>
                <li><button onClick={() => navegar("/app") }>App</button></li>
                <li><button onClick={() => navegar("/pag_params", "Titulo aleatorio", "Descrição aleatoria só pra gerar engajamento") }>Página com Params</button></li>
                <li><Link to="/consumo_api" ><button>Consumir API</button></Link></li>
            </ul>
        </header>
    )
}