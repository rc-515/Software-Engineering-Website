import React, { useEffect, useState } from "react";
import { View, Text, Button, FlatList } from "react-native";
import { getMatches, deleteMatch } from "../services/api";

export default function DashboardScreen({ route, navigation }) {
  const user = route.params?.user;
  const email = user?.email;
  const [matches, setMatches] = useState([]);

  const load = async () => {
    const res = await getMatches (email);
    setMatches(res.data)
  };

  useEffect(() => {
    load();
  }, []);

  return (
    <View style={{ padding: 20}}>
      <Text style = {{fontSize: 20}}> Welcome, {user.full_name}</Text>
      <Button title ="Create Match" onPress={() => navigation.navigate('CreateMatch', { match: item })} />
      <FlatList
        data={matches}
        keyExtractor={(item) => item.match_id.toString()}
        renderItem={({ item }) => (
          <View style={{marginVertical: 10}}>
            <Text>{item.challenger_name} vs {item.opponent_name}</Text>
            <Text>Date: {item.fight_date}</Text>
            {(item.challenger_name === email || item.opponent_name === email) && (
                <>
                  <Button title="Edit" onPress={() => navigation.navigate('EditMatch', { match: item })} />
                  <Button title="Delete" onPress={async () => {
                    await deleteMatch(item.match_id);
                    load();
                  }} />
                </>
              )}
          </View>
        )}
      />
   </View>
  );
}
