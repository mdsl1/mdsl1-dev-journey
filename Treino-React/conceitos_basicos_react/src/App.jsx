import { CapsLock_props } from './components/props.jsx';
import { CapsLock_children } from './components/children.jsx';
import { Contador } from './components/state.jsx';
import { Header } from './components/header.jsx';

function App() {

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

export default App
