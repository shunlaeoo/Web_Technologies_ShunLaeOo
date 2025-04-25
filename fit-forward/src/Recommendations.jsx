import { useRef } from "react";
import Header from "./components/Header";
import Plan from "./components/Plan";
import Footer from "./components/Footer";

function Recommendations() {
    const recommendationsRef = useRef(null);

    const handleGetStarted = () => {
      recommendationsRef.current?.scrollIntoView({ behavior: 'smooth' });
    };

    return(
        <>
            <div className="hero-section recom">
                <Header />
                <div className="hero-content backdrop-blur-sm p-10">
                    <div>
                        <h2 className="text-center font-bold leading-tight py-5">
                            Exercises
                        </h2>
                        <p className="text-lg text-center text-white pb-5">
                            Stay fit and healthy with our guided exercises 
                            designed to boost<br/>your endurance and energy levels.</p>
                        <button onClick={handleGetStarted} className="mx-auto block btn-primary text-white px-5">
                            Get Started →
                        </button>
                    </div>
                </div>
            </div>
            <Plan recommendationsRef={recommendationsRef} />
            <Footer/>
        </>
    );
}
export default Recommendations;