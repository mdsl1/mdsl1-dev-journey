import { CapsLock_props } from './components/props.jsx';
import { CapsLock_children } from './components/children.jsx';
import { Contador } from './components/state.jsx';
import { Header } from './components/header.jsx';

// Exemplo de uso de componentes por meio da página/ componente "App"
function App() {

  // Aqui não tem nenhuma tratativa especial, apenas a utilização dos componentes criados anteriormente
  return (
    <>
      <Header />

      <p>Props:</p>
      <CapsLock_props texto="texto inserido via props"/>
      
      <br></br>
      
      <p>Children:</p>
      <CapsLock_children>texto inserido no componente</CapsLock_children>

      <br></br>

      <p>States:</p>
      <Contador />

    </>
  );
}

// !Importante! Todas as páginas precisam ser exportadas dessa forma, e não com o export antes da function
export default App
