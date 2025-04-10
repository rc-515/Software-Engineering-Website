import React, { useEffect, useState } from "react";
import { SafeAreaView, View, Text, Button, FlatList, StyleSheet } from "react-native";
import { getMatches, deleteMatch } from "../services/api";


const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 20,
  },
  welcome: {
    fontSize: 20,
    marginBottom: 10,
  },
  matchItem: {
    marginVertical: 10,
    borderBottomWidth: 1,
    paddingBottom: 10,
  },
});

export default function DashboardScreen({ route, navigation }) {
  const user = route.params?.user;
  const email = user?.email;
  const [matches, setMatches] = useState([]);

  const load = async () => {
    const res = await getMatches ();
    setMatches(res.data)
  };

  useEffect(() => {
    load();
  }, []);

  const renderMatch = ({ item }) => (
    <View style={styles.matchItem}>
      <Text>{item.challenger_name} vs {item.opponent_name}</Text>
      <Text>Date: {item.fight_date}</Text>
      {(item.challenger_name === email || item.opponent_name === email) && (
        <>
          <Button title="Edit" onPress={() => navigation.navigate('EditMatch', { match: item, user })} />
          <Button title="Delete" onPress={async () => {
            await deleteMatch(item.match_id);
            load();
          }} />
        </>
      )}
    </View>
  );

  return (
    <SafeAreaView style={styles.container}>
      <Text style={styles.welcome}>Welcome, {user.full_name}</Text>
      <Button title="Create Match" onPress={() => navigation.navigate('CreateMatch', { user })} />
      <Button title="Log Out" color="red" onPress={() =>
        navigation.reset({ index: 0, routes: [{ name: 'Login' }] })
      } />
      <FlatList
        data={matches}
        keyExtractor={(item) => item.match_id.toString()}
        renderItem={renderMatch}
        contentContainerStyle={{ paddingBottom: 40 }}
      />
    </SafeAreaView>
  );
}
