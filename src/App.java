import ProxyExample.ExpensiveObject;
import ProxyExample.ExpensiveObjectProxy;

public class App {
    public static void main(String[] args) {
    ExpensiveObject object = new ExpensiveObjectProxy();
    object.process();
    object.process();
}
}
