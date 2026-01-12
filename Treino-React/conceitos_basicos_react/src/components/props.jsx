export function CapsLock_props (props) {
    const texto = props.texto;
    const textoCapsLock = texto.toUpperCase();

    return <div> { textoCapsLock } </div>
}