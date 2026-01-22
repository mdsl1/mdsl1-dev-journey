import {
    Carousel,
    CarouselContent,
    CarouselItem,
    CarouselNext,
    CarouselPrevious,
} from "@/components/ui/carousel"

export default function CarouselProducts() {
    return (
        <section className="w-full h-full flex justify-center items-center">

            <Carousel className="w-[70%]"> {/* O pai ocupa 100% do container onde está */}

                <CarouselContent className="ml-0">

                    <CarouselItem className="pl-0 h-full flex items-center justify-center">
                        <img className="w-full h-full" src="https://wallpapercave.com/wp/wp6017573.jpg" alt="" />
                    </CarouselItem>
                    
                    <CarouselItem className="pl-0 h-full flex items-center justify-center">
                        <img className="w-full h-full" src="https://wallpapers.com/images/hd/aesthetic-4k-pc-mountains-ej5hfad53oe5hlom.jpg" alt="" />
                    </CarouselItem>

                    <CarouselItem className="pl-0 h-full flex items-center justify-center">
                        <img className="w-full h-full" src="https://cdn.wallpapersafari.com/87/23/X3lUA5.jpg" alt="" />
                    </CarouselItem>

                </CarouselContent>
                    
                <CarouselPrevious className="left-4 text-white" />
                <CarouselNext className="right-4 text-white" />

            </Carousel>

        </section>
    )
}