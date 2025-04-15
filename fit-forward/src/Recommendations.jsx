import Header from "./components/Header";
import Plan from "./components/Plan";

function Recommendations() {
    return(
        <>
            <div className="hero-section recom">
                <Header />
                <div className="hero-content backdrop-blur-sm p-10">
                    <div>
                        <h1 className="text-4xl font-bold leading-tight py-5">
                            Exercises
                        </h1>
                        <p className="text-lg text-center text-white pb-5">
                            Stay fit and healthy with our guided exercises 
                            designed to boost<br/>your endurance and energy levels.</p>
                        <button className="mx-auto block btn-primary text-white px-5">
                            Get Started →
                        </button>
                    </div>
                </div>
            </div>
            <Plan />
        </>
    );
}
export default Recommendations;