import { useNavigate, Link } from "react-router-dom";
import { useState } from 'react'
import { RiMenu5Fill } from "react-icons/ri";

export default function HeaderHorizontal() {
    const [isFixed, setIsFixed] = useState(false);
    
    const class_toggle = isFixed 
        ? "text-sky-300" 
        : "hover:translate-y-0 -translate-y-11";

    function fix_header() {
        setIsFixed(!isFixed);
    }

    return (
        <header className={`bg-blue-950 ${class_toggle} hover:text-sky-300 text-blue-950 transition-all duration-300 ease-in-out w-full h-17 grid grid-cols-[47.5%_5%_47.5%] sticky top-0 outline-double outline-offset-2 outline-sky-300 mb-[2%] z-50`}>
            
            <div className="pl-10 flex justify-baseline items-center">
                <h1 className="text-3xl text-center mr-5 f-megrim">Nice Choice</h1>
            </div>
            
            <button onClick={ () => fix_header() } className={`bg-sky-600 rounded-full w-17 h-17 translate-y-7.5 duration-300 transition-all flex justify-center items-center cursor-pointer`}> <RiMenu5Fill className="text-4xl"/> </button>

            <ul className="flex justify-center gap-7 items-center">
                <li><Link to="/" className="text-2xl hover:text-sky-600">Home</Link></li>
                <li><Link to="/trabalhos" className="text-2xl hover:text-sky-600">Nosso Trabalho</Link></li>

                <li><Link to="/sobre" className="text-2xl hover:text-sky-600">Sobre Nós</Link></li>
            </ul>
        </header>
    );
}