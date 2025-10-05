// GESTIORE DEGLI STARE IN JAVASCRIPT
// IL CODICE è ISPIRATO A ZUSTAND
// QUI SOTTO UN ESEMPIO DI UTILIZZO COMMENTATO
//
// <script src="vanilla_store.js"></script>
//
// <script>
//   const userStore = VanillaStore.persist(
//   function () {
//   return {name: "Anonimo", loggedIn: false};
// },
//   {name: "user-store", storage: "local"}
//   );
//
//   userStore.setState({name: "Luca", loggedIn: true});
// </script>

(function (global) {
  function createStore(initialState, middlewares) {
    let state = initialState || {};
    let listeners = [];
    const applyMiddleware = middlewares || [];

    function setState(partial) {
      const prevState = Object.assign({}, state);
      const nextState = typeof partial === "function" ? partial(state) : partial || {};
      state = Object.assign({}, state, nextState);

      applyMiddleware.forEach(function (mw) {
        mw(prevState, state);
      });

      listeners.forEach(function (listener) {
        listener(state);
      });
    }

    function getState() {
      return state;
    }

    function subscribe(listener) {
      listeners.push(listener);
      return function () {
        listeners = listeners.filter(function (l) {
          return l !== listener;
        });
      };
    }

    function reset() {
      state = Object.assign({}, initialState);
      listeners.forEach(function (listener) {
        listener(state);
      });
    }

    return {
      getState: getState,
      setState: setState,
      subscribe: subscribe,
      reset: reset
    };
  }

  function persist(config, options) {
    const storageKey = (options && options.name) || "vanilla-store";
    const storageType = (options && options.storage) || "local";
    const storage = storageType === "session" ? sessionStorage : localStorage;
    const saved = storage.getItem(storageKey);
    const initialState = saved ? JSON.parse(saved) : config();
    const store = createStore(initialState, [logMiddleware]);
    const defaultState = config();

    store.subscribe(function (state) {
      storage.setItem(storageKey, JSON.stringify(state));
    });

    const originalReset = store.reset;
    store.reset = function () {
      originalReset();
      storage.setItem(storageKey, JSON.stringify(defaultState));
    };

    store.clear = function () {
      storage.removeItem(storageKey);
    };

    return store;
  }

  function logMiddleware(prev, next) {
    console.groupCollapsed(
        "%c🧩 VanillaStore Update",
        "color:#03A9F4;font-weight:bold"
    );
    console.log("Prev state:", prev);
    console.log("Next state:", next);
    console.groupEnd();
  }

  if (typeof module !== "undefined" && module.exports) {
    module.exports = {createStore: createStore, persist: persist};
  } else {
    global.VanillaStore = {createStore: createStore, persist: persist};
  }
})(this);
