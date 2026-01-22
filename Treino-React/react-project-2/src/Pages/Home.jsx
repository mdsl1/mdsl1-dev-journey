import { useState } from 'react'
import HeaderHorizontal from '../Components/Header1.jsx'
import "../index.css";
import CarouselProducts from '../Components/Carousel.jsx';
import HeaderVertical from '@/Components/Header2.jsx';

function Home() {
  const [count, setCount] = useState(0)

  return (
    <>
      {/*<HeaderHorizontal />*/}
      <HeaderVertical />

      <h1 className="text-5xl text-blue-600 text-center font-bold f-megrim underline my-[3%]">
        Promoções Imperdíveis
      </h1>

      <CarouselProducts />

    <div className="w-full h-screen bg-sky-200"></div>

      <div className="card">
        <button onClick={() => setCount((count) => count + 1)}>
          count is {count}
        </button>
        <p>
          Edit <code>src/App.jsx</code> and save to test HMR
        </p>
      </div>
      <p className="read-the-docs">
        Click on the Vite and React logos to learn more
      </p>
    </>
  )
}

export default Home
